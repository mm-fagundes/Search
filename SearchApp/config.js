// Configuração da API
export const API_CONFIG = {
  // Para desenvolvimento local
  BASE_URL: __DEV__ ? 'http://localhost:5000/api' : 'https://your-production-api.com/api',
  
  // Timeout para requisições (em ms)
  TIMEOUT: 10000,
  
  // Headers padrão
  DEFAULT_HEADERS: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  
  // Configurações de retry
  RETRY_ATTEMPTS: 3,
  RETRY_DELAY: 1000,
};

// Configurações do aplicativo
export const APP_CONFIG = {
  // Nome do aplicativo
  APP_NAME: 'SearchApp',
  
  // Versão
  VERSION: '1.0.0',
  
  // Configurações de cache
  CACHE_DURATION: 5 * 60 * 1000, // 5 minutos
  
  // Configurações de paginação
  DEFAULT_PAGE_SIZE: 20,
  
  // Configurações de imagem
  IMAGE_QUALITY: 0.8,
  MAX_IMAGE_SIZE: 1024 * 1024, // 1MB
};

