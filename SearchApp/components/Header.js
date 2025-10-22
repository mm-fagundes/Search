import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity, SafeAreaView } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { colors, spacing, typography, borderRadius, shadows } from '../theme';
import { useTheme } from '../contexts/ThemeContext';

const Header = ({
  title,
  subtitle,
  leftIcon,
  rightIcon,
  onLeftPress,
  onRightPress,
  backgroundColor,
  style,
  ...props
}) => {
  const { colors: themeColors } = useTheme();
  const bgColor = backgroundColor || themeColors.background;

  const styles = StyleSheet.create({
    safeArea: {
      backgroundColor: bgColor,
    },
    header: {
      backgroundColor: bgColor,
      paddingHorizontal: spacing.lg,
      paddingVertical: spacing.md,
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'space-between',
      ...shadows.sm,
      borderBottomColor: themeColors.border,
      borderBottomWidth: 1,
    },
    leftButton: {
      padding: spacing.sm,
      marginLeft: -spacing.sm,
    },
    rightButton: {
      padding: spacing.sm,
      marginRight: -spacing.sm,
    },
    titleContainer: {
      flex: 1,
      marginHorizontal: spacing.md,
    },
    title: {
      ...typography.styles.h3,
      color: themeColors.text,
    },
    subtitle: {
      ...typography.styles.bodySmall,
      color: themeColors.textSecondary,
      marginTop: spacing.xs,
    },
  });

  return (
    <SafeAreaView style={styles.safeArea}>
      <View style={[styles.header, style]} {...props}>
        {leftIcon ? (
          <TouchableOpacity
            style={styles.leftButton}
            onPress={onLeftPress}
            activeOpacity={0.7}
          >
            {typeof leftIcon === 'string' ? (
              <Ionicons name={leftIcon} size={24} color={themeColors.primary} />
            ) : (
              leftIcon
            )}
          </TouchableOpacity>
        ) : (
          <View style={{ width: 40 }} />
        )}

        <View style={styles.titleContainer}>
          {title && <Text style={styles.title}>{title}</Text>}
          {subtitle && <Text style={styles.subtitle}>{subtitle}</Text>}
        </View>

        {rightIcon ? (
          <TouchableOpacity
            style={styles.rightButton}
            onPress={onRightPress}
            activeOpacity={0.7}
          >
            {typeof rightIcon === 'string' ? (
              <Ionicons name={rightIcon} size={24} color={themeColors.primary} />
            ) : (
              rightIcon
            )}
          </TouchableOpacity>
        ) : (
          <View style={{ width: 40 }} />
        )}
      </View>
    </SafeAreaView>
  );
};

export default Header;

