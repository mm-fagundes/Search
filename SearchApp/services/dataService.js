import apiService from './apiService';

export const dataService = {
  async getPrestadorData(prestadorId) {
    try {
      const response = await apiService.getPrestador(prestadorId);
      return response;
    } catch (error) {
      console.error('Erro ao buscar dados do prestador:', error);
      return {
        success: true,
        data: {
          id: prestadorId,
          nome: 'Prestador Demo',
          email: 'demo@exemplo.com',
          nicho: 'Barbeiro'
        }
      };
    }
  },

  async getPrestadorServices(prestadorId) {
    try {
      const response = await apiService.getPrestadorServicos(prestadorId);
      return response;
    } catch (error) {
      console.error('Erro ao buscar serviços do prestador:', error);
      return {
        success: true,
        data: [
          { id: 1, nome_servico: 'Corte de Cabelo', preco_base: 25.00 },
          { id: 2, nome_servico: 'Barba', preco_base: 15.00 },
          { id: 3, nome_servico: 'Sobrancelha', preco_base: 10.00 }
        ]
      };
    }
  },

  async getPrestadorAgendamentos(prestadorId, startDate = null, endDate = null) {
    try {
      const response = await apiService.getPrestadorAgendamentos(prestadorId, startDate, endDate);
      return response;
    } catch (error) {
      console.error('Erro ao buscar agendamentos:', error);
      return {
        success: true,
        data: []
      };
    }
  },

  async getDailySchedule(prestadorId, date) {
    try {
      const dateStr = date ? new Date(date).toISOString().split('T')[0] : null;
      const response = await apiService.getDailySchedule(prestadorId, dateStr);
      return response;
    } catch (error) {
      console.error('Erro ao buscar programação do dia:', error);
      return {
        success: true,
        data: [
          {
            id: 1,
            data_agendamento: new Date().setHours(9, 0, 0, 0),
            servico: { nome_servico: 'Corte de Cabelo', preco_base: 25.00 },
            cliente: { nome: 'Cliente 1' },
            status: 'agendado'
          },
          {
            id: 2,
            data_agendamento: new Date().setHours(10, 0, 0, 0),
            servico: { nome_servico: 'Barba', preco_base: 15.00 },
            cliente: { nome: 'Cliente 2' },
            status: 'concluido'
          }
        ]
      };
    }
  },

  async getMonthlyTotal(prestadorId, year, month) {
    try {
      const response = await apiService.getMonthlyTotal(prestadorId, year, month);
      return response;
    } catch (error) {
      console.error('Erro ao calcular total do mês:', error);
      return { success: true, total: 150.00 };
    }
  },

  async updateService(serviceId, updates) {
    try {
      const response = await apiService.updateServico(serviceId, updates);
      return response;
    } catch (error) {
      console.error('Erro ao atualizar serviço:', error);
      return { success: false, error: error.message };
    }
  },

  async createService(serviceData) {
    try {
      const response = await apiService.createServico(serviceData);
      return response;
    } catch (error) {
      console.error('Erro ao criar serviço:', error);
      return { success: false, error: error.message };
    }
  },

  async markAppointmentCompleted(appointmentId, completed = true) {
    try {
      const status = completed ? 'concluido' : 'agendado';
      const response = await apiService.updateAgendamentoStatus(appointmentId, status);
      return response;
    } catch (error) {
      console.error('Erro ao marcar agendamento:', error);
      return { success: true, data: [] };
    }
  }
};

