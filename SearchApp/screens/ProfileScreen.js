import React from 'react';
import { View, Text, StyleSheet, ScrollView, SafeAreaView, TouchableOpacity } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { Card, Badge } from '../components';
import { colors, spacing, typography, borderRadius, shadows } from '../theme';
import { useTheme } from '../contexts/ThemeContext';

const ProfileScreen = ({ navigation }) => {
  const { colors: themeColors } = useTheme();

  const profileMenuItems = [
    { icon: 'person-outline', label: 'Editar Perfil', screen: 'EditProfile' },
    { icon: 'briefcase-outline', label: 'Meus Serviços', screen: 'AddService' },
    { icon: 'people-outline', label: 'Clientes Recentes', screen: 'RecentClients' },
    { icon: 'calendar-outline', label: 'Agenda', screen: 'Agenda' },
    { icon: 'document-text-outline', label: 'Relatório Mensal', screen: 'MonthlyReport' },
    { icon: 'card-outline', label: 'Conta Bancária', screen: 'BankAccount' },
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
    profileHeader: {
      alignItems: 'center',
      marginBottom: spacing['2xl'],
      paddingBottom: spacing['2xl'],
      borderBottomWidth: 1,
      borderBottomColor: themeColors.border,
    },
    avatar: {
      width: 100,
      height: 100,
      borderRadius: borderRadius.full,
      backgroundColor: themeColors.primary,
      justifyContent: 'center',
      alignItems: 'center',
      marginBottom: spacing.lg,
      ...shadows.lg,
    },
    name: {
      ...typography.styles.h3,
      color: themeColors.text,
      marginBottom: spacing.xs,
    },
    role: {
      ...typography.styles.bodySmall,
      color: themeColors.textSecondary,
      marginBottom: spacing.md,
    },
    ratingContainer: {
      flexDirection: 'row',
      alignItems: 'center',
      gap: spacing.sm,
    },
    rating: {
      ...typography.styles.body,
      color: colors.warning,
      fontWeight: '600',
    },
    statsRow: {
      flexDirection: 'row',
      justifyContent: 'space-around',
      marginTop: spacing.xl,
      paddingTop: spacing.xl,
      borderTopWidth: 1,
      borderTopColor: themeColors.border,
    },
    statItem: {
      alignItems: 'center',
    },
    statValue: {
      ...typography.styles.h4,
      color: themeColors.primary,
      marginBottom: spacing.xs,
    },
    statLabel: {
      ...typography.styles.caption,
      color: themeColors.textSecondary,
    },
    sectionTitle: {
      ...typography.styles.h4,
      color: themeColors.text,
      marginBottom: spacing.lg,
      marginTop: spacing.xl,
    },
    menuItem: {
      flexDirection: 'row',
      alignItems: 'center',
      paddingVertical: spacing.md,
      paddingHorizontal: spacing.lg,
      borderRadius: borderRadius.lg,
      marginBottom: spacing.md,
      backgroundColor: themeColors.backgroundSecondary,
      ...shadows.sm,
    },
    menuIcon: {
      width: 40,
      height: 40,
      borderRadius: borderRadius.lg,
      backgroundColor: themeColors.background,
      justifyContent: 'center',
      alignItems: 'center',
      marginRight: spacing.lg,
    },
    menuContent: {
      flex: 1,
    },
    menuLabel: {
      ...typography.styles.body,
      color: themeColors.text,
      fontWeight: '500',
    },
    menuArrow: {
      marginLeft: spacing.md,
    },
    logoutButton: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      paddingVertical: spacing.lg,
      paddingHorizontal: spacing.xl,
      borderRadius: borderRadius.lg,
      backgroundColor: `${colors.error}15`,
      marginTop: spacing.xl,
      marginBottom: spacing.xl,
    },
    logoutText: {
      ...typography.styles.button,
      color: colors.error,
      marginLeft: spacing.sm,
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
          {/* Profile Header */}
          <View style={styles.profileHeader}>
            <View style={styles.avatar}>
              <Ionicons name="person" size={50} color={colors.white} />
            </View>
            <Text style={styles.name}>João Silva</Text>
            <Text style={styles.role}>Consultor Profissional</Text>
            
            <View style={styles.ratingContainer}>
              <Ionicons name="star" size={16} color={colors.warning} />
              <Text style={styles.rating}>4.8</Text>
              <Text style={[styles.role, { marginBottom: 0 }]}>(128 avaliações)</Text>
            </View>

            <View style={styles.statsRow}>
              <View style={styles.statItem}>
                <Text style={styles.statValue}>2.5k</Text>
                <Text style={styles.statLabel}>Ganhos</Text>
              </View>
              <View style={styles.statItem}>
                <Text style={styles.statValue}>48</Text>
                <Text style={styles.statLabel}>Clientes</Text>
              </View>
              <View style={styles.statItem}>
                <Text style={styles.statValue}>95%</Text>
                <Text style={styles.statLabel}>Conclusão</Text>
              </View>
            </View>
          </View>

          {/* Menu Items */}
          <Text style={styles.sectionTitle}>Minha Conta</Text>
          {profileMenuItems.map((item, index) => (
            <TouchableOpacity
              key={index}
              style={styles.menuItem}
              onPress={() => navigation.navigate(item.screen)}
              activeOpacity={0.7}
            >
              <View style={styles.menuIcon}>
                <Ionicons name={item.icon} size={20} color={themeColors.primary} />
              </View>
              <View style={styles.menuContent}>
                <Text style={styles.menuLabel}>{item.label}</Text>
              </View>
              <View style={styles.menuArrow}>
                <Ionicons name="chevron-forward" size={20} color={themeColors.textLight} />
              </View>
            </TouchableOpacity>
          ))}

          {/* Logout Button */}
          <TouchableOpacity style={styles.logoutButton} activeOpacity={0.7}>
            <Ionicons name="log-out-outline" size={20} color={colors.error} />
            <Text style={styles.logoutText}>Sair da Conta</Text>
          </TouchableOpacity>
        </View>
      </ScrollView>
    </SafeAreaView>
  );
};

export default ProfileScreen;

