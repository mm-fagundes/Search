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

const MonthlyAppointmentsScreen = ({ navigation }) => {
  const [currentDate, setCurrentDate] = useState(new Date());
  const [selectedDate, setSelectedDate] = useState(21); // Dia 21 selecionado como no design
  const [appointments, setAppointments] = useState([]);
  const [loading, setLoading] = useState(true);
  const [userName, setUserName] = useState('Nome do Usuário');

  useEffect(() => {
    loadMonthlyAppointments();
  }, [currentDate]);

  const loadMonthlyAppointments = async () => {
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

      // Buscar agendamentos do mês atual
      const startOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
      const endOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
      
      const appointmentsResult = await dataService.getPrestadorAgendamentos(
        userId,
        startOfMonth.toISOString(),
        endOfMonth.toISOString()
      );
      
      if (appointmentsResult.success) {
        const formattedAppointments = appointmentsResult.data.map(agendamento => ({
          id: agendamento.id,
          name: agendamento.cliente?.nome || 'Cliente',
          service: agendamento.servicos?.titulo || 'Serviço',
          date: new Date(agendamento.data).toLocaleDateString('pt-BR', {
            day: 'numeric',
            month: 'long',
            hour: '2-digit',
            minute: '2-digit'
          }),
        }));
        
        setAppointments(formattedAppointments);
      }
    } catch (error) {
      console.error('Erro ao carregar agendamentos mensais:', error);
    } finally {
      setLoading(false);
    }
  };

  // Gerar dias do calendário
  const generateCalendarDays = () => {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();

    const days = [];
    
    // Adicionar dias vazios do início
    for (let i = 0; i < startingDayOfWeek; i++) {
      days.push(null);
    }
    
    // Adicionar dias do mês
    for (let day = 1; day <= daysInMonth; day++) {
      days.push(day);
    }
    
    return days;
  };

  const monthNames = [
    'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
    'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'
  ];

  const weekDays = ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'];

  const CalendarDay = ({ day, isSelected, onPress }) => (
    <TouchableOpacity
      style={[
        styles.calendarDay,
        isSelected && styles.selectedCalendarDay
      ]}
      onPress={() => onPress(day)}
      disabled={!day}
    >
      <Text style={[
        styles.calendarDayText,
        isSelected && styles.selectedCalendarDayText
      ]}>
        {day}
      </Text>
    </TouchableOpacity>
  );

  const AppointmentItem = ({ appointment }) => (
    <View style={styles.appointmentItem}>
      <View style={styles.appointmentInfo}>
        <Text style={styles.appointmentName}>{appointment.name}</Text>
        <Text style={styles.appointmentService}>{appointment.service}</Text>
      </View>
      <Text style={styles.appointmentDate}>{appointment.date}</Text>
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
        {/* Calendar Card */}
        <View style={styles.calendarContainer}>
          <View style={styles.calendarCard}>
            <Text style={styles.calendarTitle}>Agenda</Text>
            
            {/* Month Navigation */}
            <View style={styles.monthNavigation}>
              <TouchableOpacity>
                <Text style={styles.monthNavText}>←</Text>
              </TouchableOpacity>
              <Text style={styles.monthText}>
                {monthNames[currentDate.getMonth()]} {currentDate.getFullYear()}
              </Text>
              <TouchableOpacity>
                <Text style={styles.monthNavText}>→</Text>
              </TouchableOpacity>
            </View>

            {/* Week Days Header */}
            <View style={styles.weekDaysContainer}>
              {weekDays.map((day, index) => (
                <Text key={index} style={styles.weekDayText}>{day}</Text>
              ))}
            </View>

            {/* Calendar Grid */}
            <View style={styles.calendarGrid}>
              {generateCalendarDays().map((day, index) => (
                <CalendarDay
                  key={index}
                  day={day}
                  isSelected={day === selectedDate}
                  onPress={setSelectedDate}
                />
              ))}
            </View>

            {/* Legend */}
            <View style={styles.legendContainer}>
              <View style={styles.legendItem}>
                <View style={[styles.legendColor, { backgroundColor: '#E5E7EB' }]} />
                <Text style={styles.legendText}>Vago</Text>
              </View>
              <View style={styles.legendItem}>
                <View style={[styles.legendColor, { backgroundColor: '#3B82F6' }]} />
                <Text style={styles.legendText}>Preenchido</Text>
              </View>
              <View style={styles.legendItem}>
                <View style={[styles.legendColor, { backgroundColor: '#1D4ED8' }]} />
                <Text style={styles.legendText}>Cheio</Text>
              </View>
            </View>
          </View>
        </View>

        {/* Appointments List */}
        <View style={styles.appointmentsContainer}>
          {appointments.map((appointment) => (
            <AppointmentItem key={appointment.id} appointment={appointment} />
          ))}
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
  calendarContainer: {
    marginTop: -15,
    marginBottom: 20,
  },
  calendarCard: {
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
  calendarTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#1F2937',
    marginBottom: 20,
    textAlign: 'center',
  },
  monthNavigation: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
  },
  monthNavText: {
    fontSize: 18,
    color: '#6366F1',
    fontWeight: 'bold',
  },
  monthText: {
    fontSize: 16,
    fontWeight: '600',
    color: '#1F2937',
  },
  weekDaysContainer: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    marginBottom: 10,
  },
  weekDayText: {
    fontSize: 14,
    fontWeight: '600',
    color: '#6B7280',
    width: 30,
    textAlign: 'center',
  },
  calendarGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'space-around',
  },
  calendarDay: {
    width: 30,
    height: 30,
    justifyContent: 'center',
    alignItems: 'center',
    marginVertical: 2,
  },
  selectedCalendarDay: {
    backgroundColor: '#6366F1',
    borderRadius: 15,
  },
  calendarDayText: {
    fontSize: 14,
    color: '#374151',
  },
  selectedCalendarDayText: {
    color: '#FFFFFF',
    fontWeight: 'bold',
  },
  legendContainer: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    marginTop: 20,
  },
  legendItem: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  legendColor: {
    width: 12,
    height: 12,
    borderRadius: 6,
    marginRight: 5,
  },
  legendText: {
    fontSize: 12,
    color: '#6B7280',
  },
  appointmentsContainer: {
    marginBottom: 20,
  },
  appointmentItem: {
    backgroundColor: '#FFFFFF',
    borderRadius: 10,
    padding: 15,
    marginBottom: 10,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 1,
    },
    shadowOpacity: 0.1,
    shadowRadius: 2,
    elevation: 2,
  },
  appointmentInfo: {
    flex: 1,
  },
  appointmentName: {
    fontSize: 16,
    fontWeight: '600',
    color: '#1F2937',
    marginBottom: 2,
  },
  appointmentService: {
    fontSize: 14,
    color: '#6B7280',
  },
  appointmentDate: {
    fontSize: 12,
    color: '#9CA3AF',
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

export default MonthlyAppointmentsScreen;

