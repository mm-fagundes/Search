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

const HomeScreen = ({ navigation }) => {
  const [userData, setUserData] = useState({
    nome: 'Carregando...',
    totalMes: 0,
    receitas: []
  });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadUserData();
  }, []);

  const loadUserData = async () => {
    try {
      setLoading(true);
      
      // Verificar se o usuário está autenticado
      const sessionResult = await authService.getCurrentSession();
      if (!sessionResult.success || !sessionResult.session) {
        navigation.navigate('Welcome');
        return;
      }

      const userId = sessionResult.session.user.id;
      
      // Buscar dados do prestador
      const prestadorResult = await dataService.getPrestadorData(userId);
      if (prestadorResult.success) {
        setUserData(prev => ({
          ...prev,
          nome: prestadorResult.data.nome || 'Nome do Usuário'
        }));
      }

      // Calcular total do mês atual
      const currentDate = new Date();
      const totalResult = await dataService.getMonthlyTotal(
        userId,
        currentDate.getFullYear(),
        currentDate.getMonth() + 1
      );
      
      if (totalResult.success) {
        setUserData(prev => ({
          ...prev,
          totalMes: totalResult.total
        }));
      }

      // Buscar agendamentos recentes para o relatório
      const agendamentosResult = await dataService.getPrestadorAgendamentos(userId);
      if (agendamentosResult.success) {
        const receitas = agendamentosResult.data.slice(0, 3).map(agendamento => ({
          cliente: agendamento.cliente?.nome || 'Cliente',
          servico: agendamento.servicos?.titulo || 'Serviço',
          valor: agendamento.servicos?.valor || 0,
          data: new Date(agendamento.data).toLocaleDateString('pt-BR')
        }));
        
        setUserData(prev => ({
          ...prev,
          receitas
        }));
      }
    } catch (error) {
      console.error('Erro ao carregar dados do usuário:', error);
    } finally {
      setLoading(false);
    }
  };

  const IconTextButton = ({ iconName, text, onPress }) => (
    <TouchableOpacity style={styles.iconButton} onPress={onPress}>
      <View style={styles.iconContainer}>
        <Text style={styles.iconText}>{iconName}</Text>
      </View>
      <Text style={styles.iconButtonText}>{text}</Text>
    </TouchableOpacity>
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
        {/* Header com perfil do usuário */}
        <View style={styles.header}>
          <View style={styles.profileContainer}>
            <Image
              source={require('../assets/snack-icon.png')}
              style={styles.profileImage}
            />
            <Text style={styles.userName}>{userData.nome}</Text>
          </View>
        </View>

        {/* Total do mês */}
        <View style={styles.totalContainer}>
          <Text style={styles.totalText}>Total do mês: R$ {userData.totalMes.toFixed(2)}</Text>
        </View>
      </LinearGradient>

      <ScrollView style={styles.content}>
        {/* Relatório de Receitas */}
        <View style={styles.reportContainer}>
          <View style={styles.reportCard}>
            <Text style={styles.reportTitle}>Relatório de Receitas</Text>
            <View style={styles.reportTable}>
              <View style={styles.tableHeader}>
                <Text style={styles.tableHeaderText}>Cliente</Text>
                <Text style={styles.tableHeaderText}>Serviço</Text>
                <Text style={styles.tableHeaderText}>Valor</Text>
                <Text style={styles.tableHeaderText}>Data</Text>
              </View>
              {userData.receitas.map((item, index) => (
                <View key={index} style={styles.tableRow}>
                  <Text style={styles.tableCellText}>{item.cliente}</Text>
                  <Text style={styles.tableCellText}>{item.servico}</Text>
                  <Text style={styles.tableCellText}>R$ {item.valor.toFixed(2)}</Text>
                  <Text style={styles.tableCellText}>{item.data}</Text>
                </View>
              ))}
            </View>
          </View>
        </View>

        {/* Botões de ação */}
        <View style={styles.actionButtonsContainer}>
          <IconTextButton
            iconName="📋"
            text="Programação do dia"
            onPress={() => navigation.navigate('DailySchedule')}
          />
          <IconTextButton
            iconName="📅"
            text="Agendamentos do mês"
            onPress={() => navigation.navigate('MonthlyAppointments')}
          />
          <IconTextButton
            iconName="⚙️"
            text="Ajustes de trabalhos"
            onPress={() => navigation.navigate('WorkAdjustments')}
          />
        </View>
      </ScrollView>

      {/* Barra de navegação inferior */}
      <View style={styles.bottomNavigation}>
        <BottomNavButton
          iconName="🏠"
          isActive={true}
          onPress={() => console.log('Home')}
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
  },
  profileContainer: {
    alignItems: 'center',
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
  totalContainer: {
    alignItems: 'center',
    paddingHorizontal: 20,
  },
  totalText: {
    color: '#FFFFFF',
    fontSize: 18,
    fontWeight: '600',
  },
  content: {
    flex: 1,
    paddingHorizontal: 20,
  },
  reportContainer: {
    marginTop: -15,
    marginBottom: 20,
  },
  reportCard: {
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
  reportTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#1F2937',
    marginBottom: 15,
  },
  reportTable: {
    borderWidth: 1,
    borderColor: '#E5E7EB',
    borderRadius: 8,
  },
  tableHeader: {
    flexDirection: 'row',
    backgroundColor: '#F9FAFB',
    paddingVertical: 10,
    paddingHorizontal: 5,
  },
  tableHeaderText: {
    flex: 1,
    fontSize: 12,
    fontWeight: 'bold',
    color: '#374151',
    textAlign: 'center',
  },
  tableRow: {
    flexDirection: 'row',
    paddingVertical: 8,
    paddingHorizontal: 5,
    borderTopWidth: 1,
    borderTopColor: '#E5E7EB',
  },
  tableCellText: {
    flex: 1,
    fontSize: 11,
    color: '#6B7280',
    textAlign: 'center',
  },
  actionButtonsContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 20,
  },
  iconButton: {
    flex: 1,
    alignItems: 'center',
    marginHorizontal: 5,
  },
  iconContainer: {
    width: 60,
    height: 60,
    backgroundColor: '#6366F1',
    borderRadius: 15,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 8,
  },
  iconText: {
    fontSize: 24,
  },
  iconButtonText: {
    fontSize: 12,
    color: '#374151',
    textAlign: 'center',
    fontWeight: '500',
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

export default HomeScreen;

