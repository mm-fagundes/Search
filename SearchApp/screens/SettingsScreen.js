import React from 'react';
import { View, Text, StyleSheet, ScrollView, SafeAreaView, TouchableOpacity, Switch } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { Card } from '../components';
import { colors, spacing, typography, borderRadius, shadows } from '../theme';
import { useTheme } from '../contexts/ThemeContext';

const SettingsScreen = ({ navigation }) => {
  const { isDarkMode, toggleDarkMode, colors: themeColors } = useTheme();
  const [notifications, setNotifications] = React.useState(true);
  const [biometric, setBiometric] = React.useState(false);

  const settingsSections = [
    {
      title: 'Preferências',
      items: [
        {
          icon: 'notifications-outline',
          label: 'Notificações',
          value: notifications,
          onToggle: setNotifications,
          type: 'toggle',
        },
        {
          icon: 'moon-outline',
          label: 'Modo Escuro',
          value: isDarkMode,
          onToggle: toggleDarkMode,
          type: 'toggle',
        },
        {
          icon: 'finger-print-outline',
          label: 'Autenticação Biométrica',
          value: biometric,
          onToggle: setBiometric,
          type: 'toggle',
        },
      ],
    },
    {
      title: 'Conta',
      items: [
        { icon: 'lock-closed-outline', label: 'Alterar Senha', screen: 'EditProfile' },
        { icon: 'shield-outline', label: 'Privacidade', screen: 'Privacy' },
        { icon: 'document-text-outline', label: 'Termos de Serviço', screen: 'TermsOfService' },
      ],
    },
    {
      title: 'Suporte',
      items: [
        { icon: 'help-circle-outline', label: 'Ajuda', screen: 'Help' },
        { icon: 'information-circle-outline', label: 'Sobre', screen: 'About' },
        { icon: 'chatbubbles-outline', label: 'Enviar Feedback', screen: 'Feedback' },
      ],
    },
  ];

  const styles = StyleSheet.create({
    safeArea: {
      flex: 1,
      backgroundColor: themeColors.background,
    },
    scrollView: {
      flexGrow: 1,
    },
    container: {
      padding: spacing.lg,
    },
    sectionTitle: {
      ...typography.styles.h4,
      color: themeColors.text,
      marginBottom: spacing.lg,
      marginTop: spacing.xl,
    },
    settingItem: {
      flexDirection: 'row',
      alignItems: 'center',
      paddingVertical: spacing.md,
      paddingHorizontal: spacing.lg,
      borderRadius: borderRadius.lg,
      marginBottom: spacing.md,
      backgroundColor: isDarkMode ? colors.gray800 : colors.white,
      ...shadows.sm,
    },
    settingIcon: {
      width: 40,
      height: 40,
      borderRadius: borderRadius.lg,
      backgroundColor: isDarkMode ? colors.gray700 : colors.backgroundSecondary,
      justifyContent: 'center',
      alignItems: 'center',
      marginRight: spacing.lg,
    },
    settingContent: {
      flex: 1,
    },
    settingLabel: {
      ...typography.styles.body,
      color: themeColors.text,
      fontWeight: '500',
    },
    settingDescription: {
      ...typography.styles.caption,
      color: themeColors.textSecondary,
      marginTop: spacing.xs,
    },
    settingControl: {
      marginLeft: spacing.md,
    },
    appVersion: {
      ...typography.styles.caption,
      color: themeColors.textSecondary,
      textAlign: 'center',
      marginTop: spacing['2xl'],
      marginBottom: spacing.lg,
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
          {settingsSections.map((section, sectionIndex) => (
            <View key={sectionIndex}>
              <Text style={styles.sectionTitle}>{section.title}</Text>
              {section.items.map((item, itemIndex) => (
                <TouchableOpacity
                  key={itemIndex}
                  style={styles.settingItem}
                  onPress={() => item.screen && navigation.navigate(item.screen)}
                  activeOpacity={0.7}
                  disabled={item.type === 'toggle'}
                >
                  <View style={styles.settingIcon}>
                    <Ionicons name={item.icon} size={20} color={colors.primary} />
                  </View>
                  <View style={styles.settingContent}>
                    <Text style={styles.settingLabel}>{item.label}</Text>
                    {item.description && (
                      <Text style={styles.settingDescription}>{item.description}</Text>
                    )}
                  </View>
                  <View style={styles.settingControl}>
                    {item.type === 'toggle' ? (
                      <Switch
                        value={item.value}
                        onValueChange={item.onToggle}
                        trackColor={{ false: colors.gray300, true: colors.primaryLight }}
                        thumbColor={item.value ? colors.primary : colors.gray400}
                      />
                    ) : (
                      <Ionicons name="chevron-forward" size={20} color={themeColors.textLight} />
                    )}
                  </View>
                </TouchableOpacity>
              ))}
            </View>
          ))}

          <Text style={styles.appVersion}>Versão 1.0.0</Text>
        </View>
      </ScrollView>
    </SafeAreaView>
  );
};

export default SettingsScreen;

