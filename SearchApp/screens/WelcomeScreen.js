import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  Image,
  TouchableOpacity,
  SafeAreaView,
} from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import Button from '../components/Button';

const WelcomeScreen = ({ navigation }) => {
  const handleEnterPress = () => {
    navigation.navigate('Login');
  };

  const handleCreatePress = () => {
    // Futura implementação da tela de cadastro
    console.log('Criar conta pressionado');
  };

  return (
    <SafeAreaView style={styles.container}>
      <LinearGradient
        colors={['#6366F1', '#8B5CF6', '#A855F7']}
        style={styles.gradient}
      >
        <View style={styles.content}>
          {/* Logo */}
          <View style={styles.logoContainer}>
            <Image
              source={require('../assets/Img/Logo')}
              style={styles.logo}
              resizeMode="contain"
            />
          </View>

          {/* Botão Entrar */}
          <View style={styles.buttonContainer}>
            <Button
              title="Entrar"
              onPress={handleEnterPress}
              style={styles.enterButton}
            />
          </View>

          {/* Link Criar Conta */}
          <View style={styles.createAccountContainer}>
            <Text style={styles.noAccountText}>Não tem conta? </Text>
            <TouchableOpacity onPress={handleCreatePress}>
              <Text style={styles.createAccountText}>Criar</Text>
            </TouchableOpacity>
          </View>

          {/* Termos e Política */}
          <View style={styles.termsContainer}>
            <TouchableOpacity>
              <Text style={styles.termsText}>Termos de Utilização</Text>
            </TouchableOpacity>
            <Text style={styles.separator}> | </Text>
            <TouchableOpacity>
              <Text style={styles.termsText}>Política de Privacidade</Text>
            </TouchableOpacity>
          </View>
        </View>
      </LinearGradient>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  gradient: {
    flex: 1,
  },
  content: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 30,
  },
  logoContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  logo: {
    width: 150,
    height: 150,
  },
  buttonContainer: {
    width: '100%',
    marginBottom: 30,
  },
  enterButton: {
    width: '100%',
    backgroundColor: '#FFFFFF',
  },
  createAccountContainer: {
    flexDirection: 'row',
    marginBottom: 50,
  },
  noAccountText: {
    color: '#FFFFFF',
    fontSize: 16,
  },
  createAccountText: {
    color: '#FFFFFF',
    fontSize: 16,
    fontWeight: 'bold',
    textDecorationLine: 'underline',
  },
  termsContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 30,
  },
  termsText: {
    color: '#FFFFFF',
    fontSize: 12,
    textDecorationLine: 'underline',
  },
  separator: {
    color: '#FFFFFF',
    fontSize: 12,
  },
});

export default WelcomeScreen;

