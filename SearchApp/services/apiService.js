import { API_CONFIG } from '../config';
import AsyncStorage from '@react-native-async-storage/async-storage';

class ApiService {
  constructor() {
    this.cache = new Map();
  }

  async makeRequest(endpoint, options = {}) {
    const cacheKey = `${endpoint}_${JSON.stringify(options)}`;
    
    // Verificar cache para requisições GET
    if (options.method !== 'POST' && options.method !== 'PUT' && options.method !== 'DELETE') {
      const cached = this.cache.get(cacheKey);
      if (cached && Date.now() - cached.timestamp < API_CONFIG.CACHE_DURATION) {
        return cached.data;
      }
    }

    try {
      const url = `${API_CONFIG.BASE_URL}${endpoint}`;
      const config = {
        timeout: API_CONFIG.TIMEOUT,
        headers: {
          ...API_CONFIG.DEFAULT_HEADERS,
          ...options.headers,
        },
        ...options,
      };

      const response = await this.fetchWithTimeout(url, config);
      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.error || 'Erro na requisição');
      }

      // Cache para requisições GET bem-sucedidas
      if (options.method !== 'POST' && options.method !== 'PUT' && options.method !== 'DELETE') {
        this.cache.set(cacheKey, {
          data,
          timestamp: Date.now()
        });
      }

      return data;
    } catch (error) {
      console.error('Erro na API:', error);
      
      // Retornar dados em cache em caso de erro de rede
      const cached = this.cache.get(cacheKey);
      if (cached) {
        console.log('Retornando dados em cache devido ao erro');
        return cached.data;
      }
      
      throw error;
    }
  }

  async fetchWithTimeout(url, options) {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), options.timeout || API_CONFIG.TIMEOUT);
    
    try {
      const response = await fetch(url, {
        ...options,
        signal: controller.signal
      });
      clearTimeout(timeoutId);
      return response;
    } catch (error) {
      clearTimeout(timeoutId);
      throw error;
    }
  }

  clearCache() {
    this.cache.clear();
  }

  // Autenticação
  async login(email, password, userType = 'prestador') {
    return this.makeRequest('/login', {
      method: 'POST',
      body: JSON.stringify({
        email,
        password,
        user_type: userType,
      }),
    });
  }

  async register(userData, userType = 'prestador') {
    return this.makeRequest('/register', {
      method: 'POST',
      body: JSON.stringify({
        ...userData,
        user_type: userType,
      }),
    });
  }

  // Prestador
  async getPrestador(prestadorId) {
    return this.makeRequest(`/prestador/${prestadorId}`);
  }

  async getPrestadorServicos(prestadorId) {
    return this.makeRequest(`/prestador/${prestadorId}/servicos`);
  }

  async getPrestadorAgendamentos(prestadorId, startDate = null, endDate = null) {
    let endpoint = `/prestador/${prestadorId}/agendamentos`;
    const params = new URLSearchParams();
    
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    
    if (params.toString()) {
      endpoint += `?${params.toString()}`;
    }
    
    return this.makeRequest(endpoint);
  }

  async getDailySchedule(prestadorId, date = null) {
    let endpoint = `/prestador/${prestadorId}/agendamentos/dia`;
    if (date) {
      endpoint += `?date=${date}`;
    }
    return this.makeRequest(endpoint);
  }

  async getMonthlyTotal(prestadorId, year = null, month = null) {
    let endpoint = `/prestador/${prestadorId}/total-mes`;
    const params = new URLSearchParams();
    
    if (year) params.append('year', year);
    if (month) params.append('month', month);
    
    if (params.toString()) {
      endpoint += `?${params.toString()}`;
    }
    
    return this.makeRequest(endpoint);
  }

  // Serviços
  async createServico(servicoData) {
    return this.makeRequest('/servico', {
      method: 'POST',
      body: JSON.stringify(servicoData),
    });
  }

  async updateServico(servicoId, updates) {
    return this.makeRequest(`/servico/${servicoId}`, {
      method: 'PUT',
      body: JSON.stringify(updates),
    });
  }

  // Agendamentos
  async updateAgendamentoStatus(agendamentoId, status) {
    return this.makeRequest(`/agendamento/${agendamentoId}/status`, {
      method: 'PUT',
      body: JSON.stringify({ status }),
    });
  }
}

export default new ApiService();

