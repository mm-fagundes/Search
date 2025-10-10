import apiService from './apiService';
import AsyncStorage from '@react-native-async-storage/async-storage';

export const authService = {
  async signIn(email, password) {
    try {
      const response = await apiService.login(email, password, 'prestador');
      
      if (response.success) {
        // Salvar dados do usuário no AsyncStorage
        await AsyncStorage.setItem('user', JSON.stringify(response.user));
        await AsyncStorage.setItem('userType', response.user_type);
        
        return {
          success: true,
          user: response.user,
          userType: response.user_type
        };
      } else {
        return {
          success: false,
          error: response.error || 'Erro no login'
        };
      }
    } catch (error) {
      console.error('Erro no login:', error);
      return {
        success: false,
        error: 'Erro de conexão. Verifique sua internet.'
      };
    }
  },

  async signUp(userData) {
    try {
      const response = await apiService.register(userData, 'prestador');
      
      if (response.success) {
        // Salvar dados do usuário no AsyncStorage
        await AsyncStorage.setItem('user', JSON.stringify(response.user));
        await AsyncStorage.setItem('userType', response.user_type);
        
        return {
          success: true,
          user: response.user,
          userType: response.user_type
        };
      } else {
        return {
          success: false,
          error: response.error || 'Erro no cadastro'
        };
      }
    } catch (error) {
      console.error('Erro no cadastro:', error);
      return {
        success: false,
        error: 'Erro de conexão. Verifique sua internet.'
      };
    }
  },

  async signOut() {
    try {
      await AsyncStorage.removeItem('user');
      await AsyncStorage.removeItem('userType');
      return { success: true };
    } catch (error) {
      console.error('Erro no logout:', error);
      return { success: false, error: 'Erro ao fazer logout' };
    }
  },

  async getCurrentUser() {
    try {
      const userData = await AsyncStorage.getItem('user');
      const userType = await AsyncStorage.getItem('userType');
      
      if (userData && userType) {
        return {
          success: true,
          user: JSON.parse(userData),
          userType: userType
        };
      } else {
        return { success: false, user: null };
      }
    } catch (error) {
      console.error('Erro ao obter usuário atual:', error);
      return { success: false, user: null };
    }
  },

  async getCurrentSession() {
    return this.getCurrentUser();
  },

  onAuthStateChange(callback) {
    // Implementação simplificada para compatibilidade
    // Em uma implementação real, você poderia usar um listener de mudanças no AsyncStorage
    console.log('onAuthStateChange chamado');
    return () => {}; // Retorna função de cleanup
  },

  getErrorMessage(error) {
    const errorMessages = {
      'Email ou senha incorretos': 'Email ou senha incorretos',
      'Email já cadastrado': 'Este email já está cadastrado',
      'Erro de conexão': 'Erro de conexão. Verifique sua internet.',
    };

    return errorMessages[error] || error || 'Erro desconhecido';
  }
};

