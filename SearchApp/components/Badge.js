import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { colors, spacing, typography, borderRadius } from '../theme';

const Badge = ({
  label,
  variant = 'primary', // 'primary', 'secondary', 'success', 'warning', 'error'
  size = 'md', // 'sm', 'md', 'lg'
  style,
  ...props
}) => {
  const getVariantColors = () => {
    switch (variant) {
      case 'secondary':
        return {
          backgroundColor: colors.secondaryLight,
          color: colors.secondary,
        };
      case 'success':
        return {
          backgroundColor: `${colors.success}20`,
          color: colors.success,
        };
      case 'warning':
        return {
          backgroundColor: `${colors.warning}20`,
          color: colors.warning,
        };
      case 'error':
        return {
          backgroundColor: `${colors.error}20`,
          color: colors.error,
        };
      case 'primary':
      default:
        return {
          backgroundColor: `${colors.primary}20`,
          color: colors.primary,
        };
    }
  };

  const getSizeStyles = () => {
    switch (size) {
      case 'sm':
        return {
          paddingVertical: spacing.xs,
          paddingHorizontal: spacing.sm,
          fontSize: 11,
        };
      case 'lg':
        return {
          paddingVertical: spacing.sm,
          paddingHorizontal: spacing.md,
          fontSize: 14,
        };
      case 'md':
      default:
        return {
          paddingVertical: spacing.xs,
          paddingHorizontal: spacing.md,
          fontSize: 12,
        };
    }
  };

  const variantColors = getVariantColors();
  const sizeStyles = getSizeStyles();

  const styles = StyleSheet.create({
    badge: {
      backgroundColor: variantColors.backgroundColor,
      borderRadius: borderRadius.full,
      paddingVertical: sizeStyles.paddingVertical,
      paddingHorizontal: sizeStyles.paddingHorizontal,
      alignSelf: 'flex-start',
    },
    text: {
      color: variantColors.color,
      fontSize: sizeStyles.fontSize,
      fontWeight: '600',
    },
  });

  return (
    <View style={[styles.badge, style]} {...props}>
      <Text style={styles.text}>{label}</Text>
    </View>
  );
};

export default Badge;

