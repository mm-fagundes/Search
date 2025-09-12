import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  Image,
  TouchableOpacity,
  SafeAreaView,
  Alert,
} from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import Button from '../components/Button';
import InputField from '../components/InputField';
import { authService } from '../services/authService';

const LoginScreen = ({ navigation }) => {
  const [email, setEmail] = useState('');
  const [senha, setSenha] = useState('');
  const [loading, setLoading] = useState(false);

  const handleBackPress = () => {
    navigation.goBack();
  };

  const handleLoginPress = async () => {
    if (!email.trim() || !senha.trim()) {
      Alert.alert('Erro', 'Por favor, preencha todos os campos.');
      return;
    }

    setLoading(true);
    
    try {
      const result = await authService.signIn(email, senha);
      
      if (result.success) {
        // Login bem-sucedido, navegar para a tela principal
        navigation.navigate('Home');
      } else {
        Alert.alert('Erro', result.error || 'Falha no login. Verifique suas credenciais.');
      }
    } catch (error) {
      Alert.alert('Erro', 'Falha no login. Verifique suas credenciais.');
      console.error('Erro no login:', error);
    } finally {
      setLoading(false);
    }
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

          {/* Campos de entrada */}
          <View style={styles.inputContainer}>
            <InputField
              placeholder="Email"
              value={email}
              onChangeText={setEmail}
              keyboardType="email-address"
              style={styles.input}
            />
            <InputField
              placeholder="Senha"
              value={senha}
              onChangeText={setSenha}
              secureTextEntry={true}
              style={styles.input}
            />
          </View>

          {/* Botões */}
          <View style={styles.buttonContainer}>
            <View style={styles.buttonRow}>
              <Button
                title="Voltar"
                onPress={handleBackPress}
                variant="secondary"
                style={styles.backButton}
              />
              <Button
                title="Logar"
                onPress={handleLoginPress}
                disabled={loading}
                style={styles.loginButton}
              />
            </View>
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
    marginTop: -50,
  },
  logo: {
    width: 120,
    height: 120,
  },
  inputContainer: {
    width: '100%',
    marginBottom: 30,
  },
  input: {
    marginVertical: 10,
  },
  buttonContainer: {
    width: '100%',
    marginBottom: 50,
  },
  buttonRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    width: '100%',
  },
  backButton: {
    flex: 1,
    marginRight: 10,
    backgroundColor: 'transparent',
    borderColor: '#FFFFFF',
  },
  loginButton: {
    flex: 1,
    marginLeft: 10,
    backgroundColor: '#FFFFFF',
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

export default LoginScreen;

