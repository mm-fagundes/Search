/**
 * Sistema de Design Centralizado
 * Define cores, tipografia, espaçamento e outros estilos globais
 */

export const colors = {
  // Cores primárias
  primary: '#6366F1',      // Índigo moderno
  primaryLight: '#818CF8',
  primaryDark: '#4F46E5',
  
  // Cores secundárias
  secondary: '#EC4899',    // Rosa vibrante
  secondaryLight: '#F472B6',
  secondaryDark: '#DB2777',
  
  // Cores de sucesso, aviso e erro
  success: '#10B981',      // Verde esmeralda
  warning: '#F59E0B',      // Âmbar
  error: '#EF4444',        // Vermelho
  
  // Cores neutras
  white: '#FFFFFF',
  black: '#000000',
  gray50: '#F9FAFB',
  gray100: '#F3F4F6',
  gray200: '#E5E7EB',
  gray300: '#D1D5DB',
  gray400: '#9CA3AF',
  gray500: '#6B7280',
  gray600: '#4B5563',
  gray700: '#374151',
  gray800: '#1F2937',
  gray900: '#111827',
  
  // Cores de fundo
  background: '#FFFFFF',
  backgroundSecondary: '#F9FAFB',
  
  // Cores de texto
  text: '#111827',
  textSecondary: '#6B7280',
  textLight: '#9CA3AF',
  
  // Cores de borda
  border: '#E5E7EB',
  borderLight: '#F3F4F6',
};

export const typography = {
  // Tamanhos de fonte
  sizes: {
    xs: 12,
    sm: 14,
    base: 16,
    lg: 18,
    xl: 20,
    '2xl': 24,
    '3xl': 30,
    '4xl': 36,
  },
  
  // Pesos de fonte
  weights: {
    light: '300',
    normal: '400',
    medium: '500',
    semibold: '600',
    bold: '700',
    extrabold: '800',
  },
  
  // Estilos de texto predefinidos
  styles: {
    h1: {
      fontSize: 36,
      fontWeight: '700',
      lineHeight: 44,
      color: colors.text,
    },
    h2: {
      fontSize: 30,
      fontWeight: '700',
      lineHeight: 36,
      color: colors.text,
    },
    h3: {
      fontSize: 24,
      fontWeight: '600',
      lineHeight: 32,
      color: colors.text,
    },
    h4: {
      fontSize: 20,
      fontWeight: '600',
      lineHeight: 28,
      color: colors.text,
    },
    body: {
      fontSize: 16,
      fontWeight: '400',
      lineHeight: 24,
      color: colors.text,
    },
    bodySmall: {
      fontSize: 14,
      fontWeight: '400',
      lineHeight: 20,
      color: colors.textSecondary,
    },
    caption: {
      fontSize: 12,
      fontWeight: '400',
      lineHeight: 16,
      color: colors.textLight,
    },
    button: {
      fontSize: 16,
      fontWeight: '600',
      lineHeight: 20,
    },
  },
};

export const spacing = {
  xs: 4,
  sm: 8,
  md: 12,
  lg: 16,
  xl: 24,
  '2xl': 32,
  '3xl': 48,
  '4xl': 64,
};

export const borderRadius = {
  none: 0,
  sm: 4,
  md: 8,
  lg: 12,
  xl: 16,
  '2xl': 20,
  full: 9999,
};

export const shadows = {
  none: {
    shadowColor: 'transparent',
    shadowOffset: { width: 0, height: 0 },
    shadowOpacity: 0,
    shadowRadius: 0,
    elevation: 0,
  },
  sm: {
    shadowColor: colors.black,
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.05,
    shadowRadius: 2,
    elevation: 1,
  },
  md: {
    shadowColor: colors.black,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.1,
    shadowRadius: 6,
    elevation: 3,
  },
  lg: {
    shadowColor: colors.black,
    shadowOffset: { width: 0, height: 10 },
    shadowOpacity: 0.15,
    shadowRadius: 15,
    elevation: 5,
  },
  xl: {
    shadowColor: colors.black,
    shadowOffset: { width: 0, height: 20 },
    shadowOpacity: 0.2,
    shadowRadius: 25,
    elevation: 8,
  },
};

export default {
  colors,
  typography,
  spacing,
  borderRadius,
  shadows,
};

