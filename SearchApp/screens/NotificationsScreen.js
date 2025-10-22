import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { Header, Container } from '../components';
import { typography, colors } from '../theme';

const NotificationsScreen = ({ navigation }) => {
  return (
    <Container safeArea>
      <Header
        title="Notificações"
        leftIcon="arrow-back"
        onLeftPress={() => navigation.goBack()}
      />
      <View style={styles.content}>
        <Text style={styles.title}>Tela de Notificações</Text>
        <Text style={styles.subtitle}>Suas últimas notificações.</Text>
      </View>
    </Container>
  );
};

const styles = StyleSheet.create({
  content: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  title: {
    ...typography.styles.h3,
    color: colors.text,
    marginBottom: 8,
  },
  subtitle: {
    ...typography.styles.body,
    color: colors.textSecondary,
    textAlign: 'center',
  },
});

export default NotificationsScreen;
