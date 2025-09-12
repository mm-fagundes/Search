import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';

import WelcomeScreen from '../screens/WelcomeScreen';
import LoginScreen from '../screens/LoginScreen';
import HomeScreen from '../screens/HomeScreen';
import DailyScheduleScreen from '../screens/DailyScheduleScreen';
import MonthlyAppointmentsScreen from '../screens/MonthlyAppointmentsScreen';
import WorkAdjustmentsScreen from '../screens/WorkAdjustmentsScreen';

const Stack = createNativeStackNavigator();

const AppNavigator = () => {
  return (
    <NavigationContainer>
      <Stack.Navigator
        initialRouteName="Welcome"
        screenOptions={{
          headerShown: false,
        }}
      >
        <Stack.Screen name="Welcome" component={WelcomeScreen} />
        <Stack.Screen name="Login" component={LoginScreen} />
        <Stack.Screen name="Home" component={HomeScreen} />
        <Stack.Screen name="DailySchedule" component={DailyScheduleScreen} />
        <Stack.Screen name="MonthlyAppointments" component={MonthlyAppointmentsScreen} />
        <Stack.Screen name="WorkAdjustments" component={WorkAdjustmentsScreen} />
      </Stack.Navigator>
    </NavigationContainer>
  );
};

export default AppNavigator;

