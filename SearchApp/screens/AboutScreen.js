import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { Header, Container } from '../components';
import { typography, colors } from '../theme';

const AboutScreen = ({ navigation }) => {
  return (
    <Container safeArea>
      <Header
        title="Sobre"
        leftIcon="arrow-back"
        onLeftPress={() => navigation.goBack()}
      />
      <View style={styles.content}>
        <Text style={styles.title}>Sobre o Aplicativo</Text>
        <Text style={styles.subtitle}>Versão 1.0.0</Text>
        <Text style={[styles.subtitle, { marginTop: 16 }]}>Saiba mais sobre nosso aplicativo e equipe.</Text>
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

export default AboutScreen;
