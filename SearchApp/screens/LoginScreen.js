import React, { useState } from 'react';
import { View, Text, StyleSheet, ScrollView, SafeAreaView } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { Button, TextInput } from '../components';
import { colors, spacing, typography, borderRadius, shadows } from '../theme';
import { useTheme } from '../contexts/ThemeContext';

const LoginScreen = ({ navigation }) => {
  const { colors: themeColors } = useTheme();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [emailError, setEmailError] = useState('');
  const [passwordError, setPasswordError] = useState('');

  const validateEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  };

  const handleLogin = () => {
    let isValid = true;
    setEmailError('');
    setPasswordError('');

    if (!email) {
      setEmailError('Email é obrigatório');
      isValid = false;
    } else if (!validateEmail(email)) {
      setEmailError('Email inválido');
      isValid = false;
    }

    if (!password) {
      setPasswordError('Senha é obrigatória');
      isValid = false;
    } else if (password.length < 6) {
      setPasswordError('Senha deve ter pelo menos 6 caracteres');
      isValid = false;
    }

    if (isValid) {
      setLoading(true);
      // Simular chamada de API
      setTimeout(() => {
        setLoading(false);
        navigation.replace('MainTabs');
      }, 1500);
    }
  };

  const styles = StyleSheet.create({
    safeArea: {
      flex: 1,
      backgroundColor: themeColors.background,
    },
    scrollView: {
      flexGrow: 1,
    },
    container: {
      flex: 1,
      padding: spacing.xl,
      justifyContent: 'space-between',
    },
    header: {
      alignItems: 'center',
      marginBottom: spacing['3xl'],
      marginTop: spacing['2xl'],
    },
    iconContainer: {
      width: 80,
      height: 80,
      borderRadius: borderRadius.full,
      backgroundColor: themeColors.primary,
      justifyContent: 'center',
      alignItems: 'center',
      marginBottom: spacing.lg,
      ...shadows.lg,
    },
    title: {
      ...typography.styles.h2,
      textAlign: 'center',
      marginBottom: spacing.sm,
      color: themeColors.text,
    },
    subtitle: {
      ...typography.styles.bodySmall,
      textAlign: 'center',
      color: themeColors.textSecondary,
    },
    formContainer: {
      marginBottom: spacing['2xl'],
    },
    divider: {
      height: 1,
      backgroundColor: themeColors.border,
      marginVertical: spacing.xl,
    },
    socialContainer: {
      flexDirection: 'row',
      justifyContent: 'center',
      gap: spacing.lg,
      marginBottom: spacing.xl,
    },
    socialButton: {
      width: 50,
      height: 50,
      borderRadius: borderRadius.lg,
      backgroundColor: themeColors.backgroundSecondary,
      justifyContent: 'center',
      alignItems: 'center',
      ...shadows.sm,
    },
    signupContainer: {
      flexDirection: 'row',
      justifyContent: 'center',
      alignItems: 'center',
      marginTop: spacing.lg,
    },
    signupText: {
      ...typography.styles.bodySmall,
      color: themeColors.textSecondary,
    },
    signupLink: {
      ...typography.styles.bodySmall,
      color: themeColors.primary,
      fontWeight: '600',
      marginLeft: spacing.xs,
    },
    forgotPassword: {
      alignItems: 'flex-end',
      marginBottom: spacing.lg,
    },
    forgotPasswordText: {
      ...typography.styles.bodySmall,
      color: themeColors.primary,
      fontWeight: '500',
    },
  });

  return (
    <SafeAreaView style={styles.safeArea}>
      <ScrollView
        style={styles.scrollView}
        contentContainerStyle={{ flexGrow: 1 }}
        showsVerticalScrollIndicator={false}
      >
        <View style={styles.container}>
          {/* Header */}
          <View style={styles.header}>
            <View style={styles.iconContainer}>
              <Ionicons name="person-circle" size={48} color={colors.white} />
            </View>
            <Text style={styles.title}>Bem-vindo</Text>
            <Text style={styles.subtitle}>Faça login para continuar</Text>
          </View>

          {/* Form */}
          <View style={styles.formContainer}>
            <TextInput
              label="Email"
              placeholder="seu@email.com"
              value={email}
              onChangeText={setEmail}
              error={emailError}
              icon={<Ionicons name="mail-outline" size={20} color={themeColors.primary} />}
              keyboardType="email-address"
              autoCapitalize="none"
            />

            <TextInput
              label="Senha"
              placeholder="••••••••"
              value={password}
              onChangeText={setPassword}
              error={passwordError}
              secureTextEntry
              icon={<Ionicons name="lock-closed-outline" size={20} color={themeColors.primary} />}
            />

            <View style={styles.forgotPassword}>
              <Text style={styles.forgotPasswordText}>Esqueceu a senha?</Text>
            </View>

            <Button
              title="Entrar"
              onPress={handleLogin}
              loading={loading}
              fullWidth
            />
          </View>

          {/* Divider */}
          <View style={styles.divider} />

          {/* Social Login */}
          <View>
            <Text style={[styles.subtitle, { marginBottom: spacing.lg, textAlign: 'center' }]}>
              Ou continue com
            </Text>
            <View style={styles.socialContainer}>
              <View style={styles.socialButton}>
                <Ionicons name="logo-google" size={24} color={themeColors.primary} />
              </View>
              <View style={styles.socialButton}>
                <Ionicons name="logo-apple" size={24} color={themeColors.primary} />
              </View>
              <View style={styles.socialButton}>
                <Ionicons name="logo-facebook" size={24} color={themeColors.primary} />
              </View>
            </View>

            <View style={styles.signupContainer}>
              <Text style={styles.signupText}>Não tem uma conta?</Text>
              <Text style={styles.signupLink}>Cadastre-se</Text>
            </View>
          </View>
        </View>
      </ScrollView>
    </SafeAreaView>
  );
};

export default LoginScreen;

