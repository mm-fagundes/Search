import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  Image,
  TouchableOpacity,
  SafeAreaView,
  ScrollView,
  TextInput,
  Alert,
} from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import { authService } from '../services/authService';
import { dataService } from '../services/dataService';

const WorkAdjustmentsScreen = ({ navigation }) => {
  const [services, setServices] = useState([]);
  const [loading, setLoading] = useState(true);
  const [userName, setUserName] = useState('Nome do Usuário');
  const [editingService, setEditingService] = useState(null);
  const [tempPrice, setTempPrice] = useState('');

  useEffect(() => {
    loadServices();
  }, []);

  const loadServices = async () => {
    try {
      setLoading(true);
      
      // Verificar se o usuário está autenticado
      const sessionResult = await authService.getCurrentSession();
      if (!sessionResult.success || !sessionResult.session) {
        navigation.navigate('Welcome');
        return;
      }

      const userId = sessionResult.session.user.id;
      
      // Buscar dados do prestador para o nome
      const prestadorResult = await dataService.getPrestadorData(userId);
      if (prestadorResult.success) {
        setUserName(prestadorResult.data.nome || 'Nome do Usuário');
      }

      // Buscar serviços do prestador
      const servicesResult = await dataService.getPrestadorServices(userId);
      if (servicesResult.success) {
        const formattedServices = servicesResult.data.map(service => ({
          id: service.id,
          name: service.titulo,
          price: service.valor,
        }));
        
        setServices(formattedServices);
      }
    } catch (error) {
      console.error('Erro ao carregar serviços:', error);
    } finally {
      setLoading(false);
    }
  };

  const startEditing = (service) => {
    setEditingService(service.id);
    setTempPrice(service.price.toFixed(2));
  };

  const saveEdit = async (serviceId) => {
    const newPrice = parseFloat(tempPrice);
    if (isNaN(newPrice) || newPrice < 0) {
      Alert.alert('Erro', 'Por favor, insira um valor válido.');
      return;
    }

    try {
      const result = await dataService.updateService(serviceId, { valor: newPrice });
      
      if (result.success) {
        setServices(prevServices =>
          prevServices.map(service =>
            service.id === serviceId
              ? { ...service, price: newPrice }
              : service
          )
        );
        setEditingService(null);
        setTempPrice('');
      } else {
        Alert.alert('Erro', 'Falha ao atualizar o serviço.');
      }
    } catch (error) {
      console.error('Erro ao salvar edição:', error);
      Alert.alert('Erro', 'Falha ao atualizar o serviço.');
    }
  };

  const cancelEdit = () => {
    setEditingService(null);
    setTempPrice('');
  };

  const addNewService = () => {
    Alert.alert(
      'Adicionar Serviço',
      'Funcionalidade para adicionar novo serviço será implementada em breve.',
      [{ text: 'OK' }]
    );
  };

  const ServiceItem = ({ service }) => {
    const isEditing = editingService === service.id;

    return (
      <View style={styles.serviceItem}>
        <Text style={styles.serviceName}>{service.name}</Text>
        <View style={styles.priceContainer}>
          <Text style={styles.currencySymbol}>R$</Text>
          {isEditing ? (
            <TextInput
              style={styles.priceInput}
              value={tempPrice}
              onChangeText={setTempPrice}
              keyboardType="numeric"
              autoFocus
              onSubmitEditing={() => saveEdit(service.id)}
              onBlur={cancelEdit}
            />
          ) : (
            <Text style={styles.priceText}>{service.price.toFixed(2)}</Text>
          )}
          <TouchableOpacity
            style={styles.editButton}
            onPress={() => isEditing ? saveEdit(service.id) : startEditing(service)}
          >
            <Text style={styles.editButtonText}>
              {isEditing ? '✓' : '✏️'}
            </Text>
          </TouchableOpacity>
        </View>
      </View>
    );
  };

  const BottomNavButton = ({ iconName, isActive = false, onPress }) => (
    <TouchableOpacity style={styles.navButton} onPress={onPress}>
      <View style={[styles.navIcon, isActive && styles.activeNavIcon]}>
        <Text style={[styles.navIconText, isActive && styles.activeNavIconText]}>
          {iconName}
        </Text>
      </View>
    </TouchableOpacity>
  );

  return (
    <SafeAreaView style={styles.container}>
      <LinearGradient
        colors={['#6366F1', '#8B5CF6']}
        style={styles.headerGradient}
      >
        {/* Header */}
        <View style={styles.header}>
          <TouchableOpacity
            style={styles.backButton}
            onPress={() => navigation.goBack()}
          >
            <Text style={styles.backButtonText}>←</Text>
          </TouchableOpacity>
          <View style={styles.profileContainer}>
            <Image
              source={require('../assets/snack-icon.png')}
              style={styles.profileImage}
            />
            <Text style={styles.userName}>{userName}</Text>
          </View>
        </View>
      </LinearGradient>

      <ScrollView style={styles.content}>
        {/* Services Card */}
        <View style={styles.servicesContainer}>
          <View style={styles.servicesCard}>
            <Text style={styles.servicesTitle}>Editar serviços</Text>
            
            {services.map((service) => (
              <ServiceItem key={service.id} service={service} />
            ))}

            {/* Add New Service Button */}
            <TouchableOpacity
              style={styles.addButton}
              onPress={addNewService}
            >
              <View style={styles.addButtonIcon}>
                <Text style={styles.addButtonText}>+</Text>
              </View>
            </TouchableOpacity>
          </View>
        </View>
      </ScrollView>

      {/* Bottom Navigation */}
      <View style={styles.bottomNavigation}>
        <BottomNavButton
          iconName="🏠"
          onPress={() => navigation.navigate('Home')}
        />
        <BottomNavButton
          iconName="👤"
          onPress={() => console.log('Perfil')}
        />
        <BottomNavButton
          iconName="⚙️"
          onPress={() => console.log('Configurações')}
        />
      </View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#F3F4F6',
  },
  headerGradient: {
    paddingTop: 20,
    paddingBottom: 30,
  },
  header: {
    paddingHorizontal: 20,
    paddingVertical: 20,
    position: 'relative',
  },
  backButton: {
    position: 'absolute',
    left: 20,
    top: 20,
    zIndex: 1,
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: 'rgba(255, 255, 255, 0.2)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  backButtonText: {
    color: '#FFFFFF',
    fontSize: 20,
    fontWeight: 'bold',
  },
  profileContainer: {
    alignItems: 'center',
    marginTop: 10,
  },
  profileImage: {
    width: 80,
    height: 80,
    borderRadius: 40,
    marginBottom: 10,
  },
  userName: {
    color: '#FFFFFF',
    fontSize: 20,
    fontWeight: 'bold',
  },
  content: {
    flex: 1,
    paddingHorizontal: 20,
  },
  servicesContainer: {
    marginTop: -15,
    marginBottom: 20,
  },
  servicesCard: {
    backgroundColor: '#FFFFFF',
    borderRadius: 15,
    padding: 20,
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 2,
    },
    shadowOpacity: 0.1,
    shadowRadius: 3.84,
    elevation: 5,
  },
  servicesTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#1F2937',
    marginBottom: 20,
    textAlign: 'center',
  },
  serviceItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: 15,
    paddingHorizontal: 10,
    borderWidth: 1,
    borderColor: '#E5E7EB',
    borderRadius: 10,
    marginBottom: 10,
    backgroundColor: '#F9FAFB',
  },
  serviceName: {
    fontSize: 16,
    fontWeight: '600',
    color: '#1F2937',
    flex: 1,
  },
  priceContainer: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  currencySymbol: {
    fontSize: 16,
    fontWeight: '600',
    color: '#374151',
    marginRight: 5,
  },
  priceText: {
    fontSize: 16,
    fontWeight: '600',
    color: '#374151',
    minWidth: 60,
    textAlign: 'right',
  },
  priceInput: {
    fontSize: 16,
    fontWeight: '600',
    color: '#374151',
    minWidth: 60,
    textAlign: 'right',
    borderBottomWidth: 1,
    borderBottomColor: '#6366F1',
    paddingVertical: 2,
  },
  editButton: {
    marginLeft: 10,
    width: 30,
    height: 30,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#6366F1',
    borderRadius: 15,
  },
  editButtonText: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: 'bold',
  },
  addButton: {
    alignItems: 'center',
    marginTop: 20,
  },
  addButtonIcon: {
    width: 50,
    height: 50,
    backgroundColor: '#6366F1',
    borderRadius: 25,
    justifyContent: 'center',
    alignItems: 'center',
  },
  addButtonText: {
    color: '#FFFFFF',
    fontSize: 24,
    fontWeight: 'bold',
  },
  bottomNavigation: {
    flexDirection: 'row',
    backgroundColor: '#FFFFFF',
    paddingVertical: 15,
    paddingHorizontal: 20,
    justifyContent: 'space-around',
    borderTopWidth: 1,
    borderTopColor: '#E5E7EB',
  },
  navButton: {
    alignItems: 'center',
  },
  navIcon: {
    width: 40,
    height: 40,
    justifyContent: 'center',
    alignItems: 'center',
    borderRadius: 20,
  },
  activeNavIcon: {
    backgroundColor: '#6366F1',
  },
  navIconText: {
    fontSize: 20,
    color: '#9CA3AF',
  },
  activeNavIconText: {
    color: '#FFFFFF',
  },
});

export default WorkAdjustmentsScreen;

