// supabase.js
import { createClient } from '@supabase/supabase-js';

const supabaseUrl = 'https://kutdxumawtbjlqiicvea.supabase.co';
const supabaseAnonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imt1dGR4dW1hd3RiamxxaWljdmVhIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTU2MTI3NDAsImV4cCI6MjA3MTE4ODc0MH0.0aO9oW85m7WxqSpef5r6EF10_xkLCkbRMrU7GmbHLL4';

// Configurações específicas para React Native com Hermes
const supabaseOptions = {
  auth: {
    storage: null, // Usar AsyncStorage se necessário
    autoRefreshToken: true,
    persistSession: true,
    detectSessionInUrl: false
  },
  global: {
    headers: {
      'X-Client-Info': 'supabase-js-react-native'
    }
  }
};

export const supabase = createClient(supabaseUrl, supabaseAnonKey, supabaseOptions);

