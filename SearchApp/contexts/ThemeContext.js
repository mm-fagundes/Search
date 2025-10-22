import React, { createContext, useState, useContext } from 'react';
import { colors as lightColors } from '../theme';

// Cores para o modo escuro
const darkColors = {
  ...lightColors,
  primary: '#818CF8',
  primaryLight: '#A5B4FC',
  primaryDark: '#6366F1',
  
  background: '#111827',
  backgroundSecondary: '#1F2937',
  
  text: '#F9FAFB',
  textSecondary: '#D1D5DB',
  textLight: '#9CA3AF',
  
  border: '#374151',
  borderLight: '#4B5563',
  
  white: '#111827',
  gray50: '#1F2937',
  gray100: '#111827',
};

// Criar o contexto
const ThemeContext = createContext();

// Provider do contexto
export const ThemeProvider = ({ children }) => {
  const [isDarkMode, setIsDarkMode] = useState(false);

  const toggleDarkMode = () => {
    setIsDarkMode(!isDarkMode);
  };

  const currentColors = isDarkMode ? darkColors : lightColors;

  return (
    <ThemeContext.Provider value={{ isDarkMode, toggleDarkMode, colors: currentColors }}>
      {children}
    </ThemeContext.Provider>
  );
};

// Hook para usar o contexto
export const useTheme = () => {
  const context = useContext(ThemeContext);
  if (!context) {
    throw new Error('useTheme deve ser usado dentro de um ThemeProvider');
  }
  return context;
};

export default ThemeContext;

