import React, { useState } from 'react';
import { View, TextInput as RNTextInput, Text, StyleSheet, TouchableOpacity } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { colors, spacing, typography, borderRadius } from '../theme';

const TextInput = ({
  label,
  placeholder,
  value,
  onChangeText,
  error,
  disabled = false,
  secureTextEntry = false,
  icon = null,
  rightIcon = null,
  onRightIconPress,
  multiline = false,
  numberOfLines = 1,
  style,
  ...props
}) => {
  const [isFocused, setIsFocused] = useState(false);
  const [showPassword, setShowPassword] = useState(!secureTextEntry);

  const styles = StyleSheet.create({
    container: {
      marginBottom: spacing.lg,
    },
    label: {
      ...typography.styles.bodySmall,
      fontWeight: '600',
      marginBottom: spacing.sm,
      color: error ? colors.error : colors.text,
    },
    inputContainer: {
      flexDirection: 'row',
      alignItems: multiline ? 'flex-start' : 'center',
      borderRadius: borderRadius.lg,
      borderWidth: 1.5,
      borderColor: isFocused ? colors.primary : (error ? colors.error : colors.border),
      backgroundColor: disabled ? colors.gray100 : colors.white,
      paddingHorizontal: spacing.md,
      paddingVertical: multiline ? spacing.md : 0,
      minHeight: 44,
    },
    input: {
      flex: 1,
      ...typography.styles.body,
      color: colors.text,
      paddingVertical: spacing.md,
      paddingHorizontal: spacing.sm,
    },
    icon: {
      marginRight: spacing.sm,
    },
    rightIconContainer: {
      marginLeft: spacing.sm,
    },
    errorText: {
      ...typography.styles.caption,
      color: colors.error,
      marginTop: spacing.sm,
    },
    helperText: {
      ...typography.styles.caption,
      color: colors.textLight,
      marginTop: spacing.sm,
    },
  });

  return (
    <View style={[styles.container, style]}>
      {label && <Text style={styles.label}>{label}</Text>}
      
      <View style={styles.inputContainer}>
        {icon && <View style={styles.icon}>{icon}</View>}
        
        <RNTextInput
          style={styles.input}
          placeholder={placeholder}
          placeholderTextColor={colors.textLight}
          value={value}
          onChangeText={onChangeText}
          editable={!disabled}
          secureTextEntry={secureTextEntry && !showPassword}
          multiline={multiline}
          numberOfLines={numberOfLines}
          onFocus={() => setIsFocused(true)}
          onBlur={() => setIsFocused(false)}
          {...props}
        />
        
        {secureTextEntry && (
          <TouchableOpacity
            style={styles.rightIconContainer}
            onPress={() => setShowPassword(!showPassword)}
          >
            <Ionicons
              name={showPassword ? 'eye' : 'eye-off'}
              size={20}
              color={colors.textSecondary}
            />
          </TouchableOpacity>
        )}
        
        {rightIcon && !secureTextEntry && (
          <TouchableOpacity
            style={styles.rightIconContainer}
            onPress={onRightIconPress}
          >
            {rightIcon}
          </TouchableOpacity>
        )}
      </View>
      
      {error && <Text style={styles.errorText}>{error}</Text>}
    </View>
  );
};

export default TextInput;

