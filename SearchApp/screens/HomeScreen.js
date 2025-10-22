import React from 'react';
import { View, Text, StyleSheet, ScrollView, SafeAreaView, TouchableOpacity } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { Card, Badge } from '../components';
import { colors, spacing, typography, borderRadius, shadows } from '../theme';
import { useTheme } from '../contexts/ThemeContext';

const HomeScreen = ({ navigation }) => {
  const { colors: themeColors } = useTheme();

  const stats = [
    { icon: 'briefcase', label: 'Serviços', value: '12', color: themeColors.primary },
    { icon: 'people', label: 'Clientes', value: '48', color: themeColors.secondary },
    { icon: 'calendar', label: 'Agendamentos', value: '8', color: colors.success },
    { icon: 'cash', label: 'Ganhos', value: 'R$ 2.5k', color: colors.warning },
  ];

  const recentActivities = [
    { id: 1, type: 'appointment', title: 'Consulta com João Silva', time: 'Hoje às 14:30', status: 'confirmado' },
    { id: 2, type: 'payment', title: 'Pagamento recebido', time: 'Ontem às 10:15', status: 'sucesso' },
    { id: 3, type: 'client', title: 'Novo cliente: Maria Santos', time: '2 dias atrás', status: 'novo' },
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
    header: {
      marginBottom: spacing['2xl'],
    },
    greeting: {
      ...typography.styles.h2,
      color: themeColors.text,
      marginBottom: spacing.xs,
    },
    date: {
      ...typography.styles.bodySmall,
      color: themeColors.textSecondary,
    },
    statsContainer: {
      flexDirection: 'row',
      flexWrap: 'wrap',
      gap: spacing.lg,
      marginBottom: spacing['2xl'],
    },
    statCard: {
      flex: 1,
      minWidth: '45%',
      borderRadius: borderRadius.lg,
      padding: spacing.lg,
      backgroundColor: themeColors.backgroundSecondary,
      ...shadows.md,
    },
    statIcon: {
      width: 48,
      height: 48,
      borderRadius: borderRadius.lg,
      justifyContent: 'center',
      alignItems: 'center',
      marginBottom: spacing.md,
    },
    statLabel: {
      ...typography.styles.bodySmall,
      color: themeColors.textSecondary,
      marginBottom: spacing.xs,
    },
    statValue: {
      ...typography.styles.h3,
      color: themeColors.text,
    },
    sectionTitle: {
      ...typography.styles.h4,
      color: themeColors.text,
      marginBottom: spacing.lg,
      marginTop: spacing.xl,
    },
    activityCard: {
      marginBottom: spacing.lg,
    },
    activityHeader: {
      flexDirection: 'row',
      alignItems: 'center',
      marginBottom: spacing.md,
    },
    activityIcon: {
      width: 40,
      height: 40,
      borderRadius: borderRadius.lg,
      justifyContent: 'center',
      alignItems: 'center',
      marginRight: spacing.md,
    },
    activityContent: {
      flex: 1,
    },
    activityTitle: {
      ...typography.styles.body,
      color: themeColors.text,
      marginBottom: spacing.xs,
    },
    activityTime: {
      ...typography.styles.caption,
      color: themeColors.textLight,
    },
    activityFooter: {
      flexDirection: 'row',
      justifyContent: 'space-between',
      alignItems: 'center',
    },
    actionButton: {
      flexDirection: 'row',
      alignItems: 'center',
      paddingVertical: spacing.md,
      paddingHorizontal: spacing.lg,
      backgroundColor: themeColors.backgroundSecondary,
      borderRadius: borderRadius.lg,
      marginTop: spacing.xl,
      marginBottom: spacing.lg,
    },
    actionButtonText: {
      ...typography.styles.button,
      color: themeColors.primary,
      marginLeft: spacing.sm,
    },
  });

  const getActivityIconColor = (status) => {
    switch (status) {
      case 'confirmado':
        return colors.success;
      case 'sucesso':
        return colors.success;
      case 'novo':
        return themeColors.primary;
      default:
        return themeColors.textSecondary;
    }
  };

  const getActivityIcon = (type) => {
    switch (type) {
      case 'appointment':
        return 'calendar';
      case 'payment':
        return 'cash';
      case 'client':
        return 'person-add';
      default:
        return 'information-circle';
    }
  };

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
            <Text style={styles.greeting}>Olá, Profissional!</Text>
            <Text style={styles.date}>{new Date().toLocaleDateString('pt-BR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</Text>
          </View>

          {/* Stats */}
          <View style={styles.statsContainer}>
            {stats.map((stat, index) => (
              <View key={index} style={styles.statCard}>
                <View style={[styles.statIcon, { backgroundColor: `${stat.color}15` }]}>
                  <Ionicons name={stat.icon} size={24} color={stat.color} />
                </View>
                <Text style={styles.statLabel}>{stat.label}</Text>
                <Text style={styles.statValue}>{stat.value}</Text>
              </View>
            ))}
          </View>

          {/* Action Buttons */}
          <TouchableOpacity style={styles.actionButton}>
            <Ionicons name="add-circle" size={24} color={themeColors.primary} />
            <Text style={styles.actionButtonText}>Novo Agendamento</Text>
          </TouchableOpacity>

          {/* Recent Activities */}
          <Text style={styles.sectionTitle}>Atividades Recentes</Text>
          {recentActivities.map((activity) => (
            <Card key={activity.id} style={styles.activityCard}>
              <View style={styles.activityHeader}>
                <View style={[styles.activityIcon, { backgroundColor: `${getActivityIconColor(activity.status)}15` }]}>
                  <Ionicons name={getActivityIcon(activity.type)} size={20} color={getActivityIconColor(activity.status)} />
                </View>
                <View style={styles.activityContent}>
                  <Text style={styles.activityTitle}>{activity.title}</Text>
                  <Text style={styles.activityTime}>{activity.time}</Text>
                </View>
              </View>
              <View style={styles.activityFooter}>
                <Badge label={activity.status} variant={activity.status === 'novo' ? 'primary' : 'success'} size="sm" />
                <Ionicons name="chevron-forward" size={20} color={themeColors.textLight} />
              </View>
            </Card>
          ))}
        </View>
      </ScrollView>
    </SafeAreaView>
  );
};

export default HomeScreen;

