import React from 'react';
import { TouchableOpacity, Text, StyleSheet, ActivityIndicator, View } from 'react-native';
import { colors, spacing, typography, borderRadius, shadows } from '../theme';

const Button = ({
  onPress,
  title,
  variant = 'primary', // 'primary', 'secondary', 'outline', 'ghost'
  size = 'md', // 'sm', 'md', 'lg'
  disabled = false,
  loading = false,
  icon = null,
  fullWidth = false,
  style,
  ...props
}) => {
  const getVariantStyles = () => {
    switch (variant) {
      case 'secondary':
        return {
          backgroundColor: colors.secondary,
          borderColor: colors.secondary,
        };
      case 'outline':
        return {
          backgroundColor: colors.white,
          borderColor: colors.primary,
          borderWidth: 2,
        };
      case 'ghost':
        return {
          backgroundColor: 'transparent',
          borderColor: 'transparent',
        };
      case 'primary':
      default:
        return {
          backgroundColor: colors.primary,
          borderColor: colors.primary,
        };
    }
  };

  const getSizeStyles = () => {
    switch (size) {
      case 'sm':
        return {
          paddingVertical: spacing.sm,
          paddingHorizontal: spacing.md,
          minHeight: 36,
        };
      case 'lg':
        return {
          paddingVertical: spacing.lg,
          paddingHorizontal: spacing.xl,
          minHeight: 56,
        };
      case 'md':
      default:
        return {
          paddingVertical: spacing.md,
          paddingHorizontal: spacing.lg,
          minHeight: 44,
        };
    }
  };

  const getTextColor = () => {
    if (variant === 'outline' || variant === 'ghost') {
      return colors.primary;
    }
    return colors.white;
  };

  const getDisabledStyles = () => {
    if (disabled) {
      return {
        opacity: 0.5,
      };
    }
    return {};
  };

  const styles = StyleSheet.create({
    button: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      borderRadius: borderRadius.lg,
      ...getSizeStyles(),
      ...getVariantStyles(),
      ...getDisabledStyles(),
      ...shadows.md,
    },
    text: {
      ...typography.styles.button,
      color: getTextColor(),
      marginLeft: icon ? spacing.sm : 0,
    },
    container: {
      width: fullWidth ? '100%' : 'auto',
    },
  });

  return (
    <View style={[styles.container, style]}>
      <TouchableOpacity
        style={styles.button}
        onPress={onPress}
        disabled={disabled || loading}
        activeOpacity={0.7}
        {...props}
      >
        {loading ? (
          <ActivityIndicator color={getTextColor()} size="small" />
        ) : (
          <>
            {icon && <View style={{ marginRight: spacing.sm }}>{icon}</View>}
            <Text style={styles.text}>{title}</Text>
          </>
        )}
      </TouchableOpacity>
    </View>
  );
};

export default Button;

