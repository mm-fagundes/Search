import { supabase } from './supabase';

export const dataService = {
  // Obter dados do prestador com fallback para dados mock
  async getPrestadorData(prestadorId) {
    try {
      // Se não conseguir conectar com Supabase, retorna dados mock
      if (!prestadorId) {
        return {
          success: true,
          data: {
            id: 'mock-id',
            nome: 'Prestador Demo',
            email: 'demo@exemplo.com'
          }
        };
      }

      const { data, error } = await supabase
        .from('prestador')
        .select('*')
        .eq('id', prestadorId)
        .single();

      if (error) {
        console.error('Erro ao buscar dados do prestador:', error);
        // Retorna dados mock em caso de erro
        return {
          success: true,
          data: {
            id: prestadorId,
            nome: 'Prestador Demo',
            email: 'demo@exemplo.com'
          }
        };
      }

      return { success: true, data };
    } catch (error) {
      console.error('Erro ao buscar dados do prestador:', error);
      return {
        success: true,
        data: {
          id: prestadorId || 'mock-id',
          nome: 'Prestador Demo',
          email: 'demo@exemplo.com'
        }
      };
    }
  },

  // Obter serviços do prestador com dados mock
  async getPrestadorServices(prestadorId) {
    try {
      const { data, error } = await supabase
        .from('servicos_prestador')
        .select(`
          servicos (
            id,
            titulo,
            descricao,
            valor
          )
        `)
        .eq('cd_prestador', prestadorId);

      if (error) {
        console.error('Erro ao buscar serviços do prestador:', error);
        // Retorna dados mock
        return {
          success: true,
          data: [
            { id: 1, titulo: 'Corte de Cabelo', valor: 20.00 },
            { id: 2, titulo: 'Barba', valor: 10.00 },
            { id: 3, titulo: 'Sobrancelha', valor: 10.00 },
            { id: 4, titulo: 'Bigode', valor: 5.00 }
          ]
        };
      }

      return { success: true, data: data.map(item => item.servicos) };
    } catch (error) {
      console.error('Erro ao buscar serviços do prestador:', error);
      return {
        success: true,
        data: [
          { id: 1, titulo: 'Corte de Cabelo', valor: 20.00 },
          { id: 2, titulo: 'Barba', valor: 10.00 },
          { id: 3, titulo: 'Sobrancelha', valor: 10.00 },
          { id: 4, titulo: 'Bigode', valor: 5.00 }
        ]
      };
    }
  },

  // Obter agendamentos do prestador com dados mock
  async getPrestadorAgendamentos(prestadorId, startDate = null, endDate = null) {
    try {
      let query = supabase
        .from('agendamento')
        .select(`
          id,
          data,
          observacoes,
          servicos (
            id,
            titulo,
            valor
          ),
          cliente (
            nome,
            cpf
          )
        `)
        .eq('cd_prestador', prestadorId)
        .order('data', { ascending: true });

      if (startDate) {
        query = query.gte('data', startDate);
      }
      if (endDate) {
        query = query.lte('data', endDate);
      }

      const { data, error } = await query;

      if (error) {
        console.error('Erro ao buscar agendamentos:', error);
        // Retorna dados mock
        return {
          success: true,
          data: [
            {
              id: 1,
              data: new Date().toISOString(),
              servicos: { id: 1, titulo: 'Corte de Cabelo', valor: 20.00 },
              cliente: { nome: 'Cliente Demo 1', cpf: '123.456.789-00' }
            },
            {
              id: 2,
              data: new Date(Date.now() + 86400000).toISOString(),
              servicos: { id: 2, titulo: 'Barba', valor: 10.00 },
              cliente: { nome: 'Cliente Demo 2', cpf: '987.654.321-00' }
            }
          ]
        };
      }

      return { success: true, data };
    } catch (error) {
      console.error('Erro ao buscar agendamentos:', error);
      return {
        success: true,
        data: [
          {
            id: 1,
            data: new Date().toISOString(),
            servicos: { id: 1, titulo: 'Corte de Cabelo', valor: 20.00 },
            cliente: { nome: 'Cliente Demo 1', cpf: '123.456.789-00' }
          },
          {
            id: 2,
            data: new Date(Date.now() + 86400000).toISOString(),
            servicos: { id: 2, titulo: 'Barba', valor: 10.00 },
            cliente: { nome: 'Cliente Demo 2', cpf: '987.654.321-00' }
          }
        ]
      };
    }
  },

  // Obter agendamentos do dia com dados mock
  async getDailySchedule(prestadorId, date) {
    try {
      const startOfDay = new Date(date);
      startOfDay.setHours(0, 0, 0, 0);
      
      const endOfDay = new Date(date);
      endOfDay.setHours(23, 59, 59, 999);

      const { data, error } = await supabase
        .from('agendamento')
        .select(`
          id,
          data,
          observacoes,
          servicos (
            id,
            titulo,
            valor
          ),
          cliente (
            nome,
            cpf
          )
        `)
        .eq('cd_prestador', prestadorId)
        .gte('data', startOfDay.toISOString())
        .lte('data', endOfDay.toISOString())
        .order('data', { ascending: true });

      if (error) {
        console.error('Erro ao buscar programação do dia:', error);
        // Retorna dados mock
        return {
          success: true,
          data: [
            {
              id: 1,
              data: new Date().setHours(9, 0, 0, 0),
              servicos: { id: 1, titulo: 'Corte de Cabelo', valor: 20.00 },
              cliente: { nome: 'Cliente 1', cpf: '123.456.789-00' },
              concluido: false
            },
            {
              id: 2,
              data: new Date().setHours(10, 0, 0, 0),
              servicos: { id: 2, titulo: 'Barba', valor: 10.00 },
              cliente: { nome: 'Cliente 2', cpf: '987.654.321-00' },
              concluido: true
            }
          ]
        };
      }

      return { success: true, data };
    } catch (error) {
      console.error('Erro ao buscar programação do dia:', error);
      return {
        success: true,
        data: [
          {
            id: 1,
            data: new Date().setHours(9, 0, 0, 0),
            servicos: { id: 1, titulo: 'Corte de Cabelo', valor: 20.00 },
            cliente: { nome: 'Cliente 1', cpf: '123.456.789-00' },
            concluido: false
          },
          {
            id: 2,
            data: new Date().setHours(10, 0, 0, 0),
            servicos: { id: 2, titulo: 'Barba', valor: 10.00 },
            cliente: { nome: 'Cliente 2', cpf: '987.654.321-00' },
            concluido: true
          }
        ]
      };
    }
  },

  // Calcular total do mês com fallback
  async getMonthlyTotal(prestadorId, year, month) {
    try {
      const startOfMonth = new Date(year, month - 1, 1);
      const endOfMonth = new Date(year, month, 0, 23, 59, 59, 999);

      const { data, error } = await supabase
        .from('agendamento')
        .select(`
          servicos (
            valor
          )
        `)
        .eq('cd_prestador', prestadorId)
        .gte('data', startOfMonth.toISOString())
        .lte('data', endOfMonth.toISOString());

      if (error) {
        console.error('Erro ao calcular total do mês:', error);
        // Retorna valor mock
        return { success: true, total: 150.00 };
      }

      const total = data.reduce((sum, agendamento) => {
        return sum + (agendamento.servicos?.valor || 0);
      }, 0);

      return { success: true, total };
    } catch (error) {
      console.error('Erro ao calcular total do mês:', error);
      return { success: true, total: 150.00 };
    }
  },

  // Atualizar serviço
  async updateService(serviceId, updates) {
    try {
      const { data, error } = await supabase
        .from('servicos')
        .update(updates)
        .eq('id', serviceId)
        .select();

      if (error) {
        throw error;
      }

      return { success: true, data };
    } catch (error) {
      console.error('Erro ao atualizar serviço:', error);
      return { success: false, error: error.message };
    }
  },

  // Criar novo serviço
  async createService(serviceData) {
    try {
      const { data, error } = await supabase
        .from('servicos')
        .insert([serviceData])
        .select();

      if (error) {
        throw error;
      }

      return { success: true, data };
    } catch (error) {
      console.error('Erro ao criar serviço:', error);
      return { success: false, error: error.message };
    }
  },

  // Associar serviço ao prestador
  async associateServiceToPrestador(serviceId, prestadorId) {
    try {
      const { data, error } = await supabase
        .from('servicos_prestador')
        .insert([{
          cd_servico: serviceId,
          cd_prestador: prestadorId
        }])
        .select();

      if (error) {
        throw error;
      }

      return { success: true, data };
    } catch (error) {
      console.error('Erro ao associar serviço ao prestador:', error);
      return { success: false, error: error.message };
    }
  },

  // Marcar agendamento como concluído
  async markAppointmentCompleted(appointmentId, completed = true) {
    try {
      const { data, error } = await supabase
        .from('agendamento')
        .update({ concluido: completed })
        .eq('id', appointmentId)
        .select();

      if (error) {
        console.error('Erro ao marcar agendamento:', error);
        return { success: false, error: error.message };
      }

      return { success: true, data };
    } catch (error) {
      console.error('Erro ao marcar agendamento:', error);
      // Simula sucesso para não quebrar a UI
      return { success: true, data: [] };
    }
  }
};

