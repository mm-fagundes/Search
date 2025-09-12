import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  Image,
  TouchableOpacity,
  SafeAreaView,
  ScrollView,
} from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import { authService } from '../services/authService';
import { dataService } from '../services/dataService';

const DailyScheduleScreen = ({ navigation }) => {
  const [scheduleData, setScheduleData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [userName, setUserName] = useState('Nome do Usuário');

  useEffect(() => {
    loadDailySchedule();
  }, []);

  const loadDailySchedule = async () => {
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

      // Buscar programação do dia atual
      const today = new Date();
      const scheduleResult = await dataService.getDailySchedule(userId, today);
      
      if (scheduleResult.success) {
        const formattedSchedule = scheduleResult.data.map(agendamento => ({
          id: agendamento.id,
          time: new Date(agendamento.data).toLocaleTimeString('pt-BR', { 
            hour: '2-digit', 
            minute: '2-digit' 
          }),
          client: agendamento.cliente?.nome || 'Cliente',
          service: agendamento.servicos?.titulo || 'Serviço',
          completed: agendamento.concluido || false,
        }));
        
        setScheduleData(formattedSchedule);
      }
    } catch (error) {
      console.error('Erro ao carregar programação do dia:', error);
    } finally {
      setLoading(false);
    }
  };

  const toggleCompletion = async (id) => {
    try {
      const item = scheduleData.find(item => item.id === id);
      if (!item) return;

      const result = await dataService.markAppointmentCompleted(id, !item.completed);
      
      if (result.success) {
        setScheduleData(prevData =>
          prevData.map(item =>
            item.id === id ? { ...item, completed: !item.completed } : item
          )
        );
      }
    } catch (error) {
      console.error('Erro ao marcar agendamento:', error);
    }
  };

  const ScheduleItem = ({ item }) => (
    <View style={styles.scheduleItem}>
      <Text style={styles.timeText}>{item.time}</Text>
      <View style={styles.clientInfo}>
        <Text style={styles.clientName}>{item.client}</Text>
        <Text style={styles.serviceName}>{item.service}</Text>
      </View>
      <TouchableOpacity
        style={[
          styles.checkButton,
          item.completed && styles.checkButtonCompleted
        ]}
        onPress={() => toggleCompletion(item.id)}
      >
        {item.completed && <Text style={styles.checkMark}>✓</Text>}
      </TouchableOpacity>
    </View>
  );

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
        {/* Schedule Card */}
        <View style={styles.scheduleContainer}>
          <View style={styles.scheduleCard}>
            <Text style={styles.scheduleTitle}>Programa do dia</Text>
            {scheduleData.map((item) => (
              <ScheduleItem key={item.id} item={item} />
            ))}
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
  scheduleContainer: {
    marginTop: -15,
    marginBottom: 20,
  },
  scheduleCard: {
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
  scheduleTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#1F2937',
    marginBottom: 20,
    textAlign: 'center',
  },
  scheduleItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 15,
    borderBottomWidth: 1,
    borderBottomColor: '#E5E7EB',
  },
  timeText: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#374151',
    width: 60,
  },
  clientInfo: {
    flex: 1,
    marginLeft: 15,
  },
  clientName: {
    fontSize: 16,
    fontWeight: '600',
    color: '#1F2937',
    marginBottom: 2,
  },
  serviceName: {
    fontSize: 14,
    color: '#6B7280',
  },
  checkButton: {
    width: 30,
    height: 30,
    borderRadius: 15,
    borderWidth: 2,
    borderColor: '#D1D5DB',
    justifyContent: 'center',
    alignItems: 'center',
  },
  checkButtonCompleted: {
    backgroundColor: '#6366F1',
    borderColor: '#6366F1',
  },
  checkMark: {
    color: '#FFFFFF',
    fontSize: 16,
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

export default DailyScheduleScreen;

