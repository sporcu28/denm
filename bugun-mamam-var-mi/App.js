import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { StatusBar } from 'expo-status-bar';
import { StyleSheet } from 'react-native';
import { app, ensureAuth } from './firebaseConfig';
import HomeScreen from './HomeScreen';
import AdminScreen from './AdminScreen';

const Tab = createBottomTabNavigator();

export default function App() {
  React.useEffect(() => {
    ensureAuth();
  }, []);
  return (
    <NavigationContainer>
      <Tab.Navigator>
        <Tab.Screen name="Harita" component={HomeScreen} />
        <Tab.Screen name="Admin Panel" component={AdminScreen} />
      </Tab.Navigator>
    </NavigationContainer>
  );
}

const styles = StyleSheet.create({});
