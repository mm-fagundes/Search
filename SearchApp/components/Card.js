import React from 'react';
import { View, StyleSheet } from 'react-native';
import { spacing, borderRadius, shadows } from '../theme';
import { useTheme } from '../contexts/ThemeContext';

const Card = ({
  children,
  variant = 'elevated', // 'elevated', 'outlined', 'filled'
  padding = 'md',
  style,
  ...props
}) => {
  const { colors: themeColors, isDarkMode } = useTheme();

  const getPaddingValue = () => {
    const paddingMap = {
      sm: spacing.md,
      md: spacing.lg,
      lg: spacing.xl,
    };
    return paddingMap[padding] || spacing.lg;
  };

  const getVariantStyles = () => {
    switch (variant) {
      case 'outlined':
        return {
          backgroundColor: themeColors.background,
          borderWidth: 1,
          borderColor: themeColors.border,
          ...shadows.none,
        };
      case 'filled':
        return {
          backgroundColor: themeColors.backgroundSecondary,
          borderWidth: 0,
          ...shadows.none,
        };
      case 'elevated':
      default:
        return {
          backgroundColor: themeColors.backgroundSecondary,
          borderWidth: 0,
          ...shadows.md,
        };
    }
  };

  const styles = StyleSheet.create({
    card: {
      borderRadius: borderRadius.lg,
      padding: getPaddingValue(),
      ...getVariantStyles(),
    },
  });

  return (
    <View style={[styles.card, style]} {...props}>
      {children}
    </View>
  );
};

export default Card;

