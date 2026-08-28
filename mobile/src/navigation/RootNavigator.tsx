import React from "react";
import { NavigationContainer } from "@react-navigation/native";
import { createNativeStackNavigator } from "@react-navigation/native-stack";
import { createBottomTabNavigator } from "@react-navigation/bottom-tabs";

import { useAuth } from "../context/AuthContext";
import FloatingTabBar from "./FloatingTabBar";

import SplashScreen from "../screens/SplashScreen";
import GetStartedScreen from "../screens/GetStartedScreen";
import LoginScreen from "../screens/LoginScreen";
import RegisterScreen from "../screens/RegisterScreen";
import HomeScreen from "../screens/HomeScreen";
import ScheduleListScreen from "../screens/ScheduleListScreen";
import ScheduleDetailScreen from "../screens/ScheduleDetailScreen";
import SeatSelectionScreen from "../screens/SeatSelectionScreen";
import CheckoutScreen from "../screens/CheckoutScreen";
import TicketDetailScreen from "../screens/TicketDetailScreen";
import BookingHistoryScreen from "../screens/BookingHistoryScreen";
import CharterScreen from "../screens/CharterScreen";
import PromoScreen from "../screens/PromoScreen";
import ProfileScreen from "../screens/ProfileScreen";
import HelpScreen from "../screens/HelpScreen";

export type RootStackParamList = {
  Splash: undefined;
  GetStarted: undefined;
  Login: undefined;
  Register: undefined;
  MainTabs: { screen?: string } | undefined;
  Schedules: { origin?: string; destination?: string } | undefined;
  ScheduleList: { origin?: string; destination?: string } | undefined;
  ScheduleDetail: { scheduleId: number };
  SeatSelection: { scheduleId: number };
  Checkout: { scheduleId: number; selectedSeats: number[]; totalPrice: number };
  TicketDetail: {
    bookingId?: number;
    booking?: any;
    schedule?: any;
    selectedSeats?: number[];
  };
  BookingHistory: undefined;
  Charter: undefined;
  Promo: undefined;
  Help: undefined;
  Profile: undefined;
};

export type MainTabParamList = {
  Home: undefined;
  Schedules: undefined;
  BookingHistory: undefined;
  Help: undefined;
  Profile: undefined;
};

const Stack = createNativeStackNavigator<RootStackParamList>();
const Tab = createBottomTabNavigator<MainTabParamList>();

function MainTabs() {
  return (
    <Tab.Navigator
      id="main-tabs"
      tabBar={(props) => <FloatingTabBar {...props} />}
      screenOptions={{
        headerShown: false,
      }}
    >
      <Tab.Screen name="Home" component={HomeScreen} />
      <Tab.Screen name="Schedules" component={ScheduleListScreen} />
      <Tab.Screen name="BookingHistory" component={BookingHistoryScreen} />
      <Tab.Screen name="Help" component={HelpScreen} />
      <Tab.Screen name="Profile" component={ProfileScreen} />
    </Tab.Navigator>
  );
}

export default function RootNavigator() {
  const { user } = useAuth();

  return (
    <NavigationContainer>
      <Stack.Navigator
        id="root-stack"
        initialRouteName="Splash"
        screenOptions={{
          headerShown: false,
          animation: "slide_from_right",
        }}
      >
        <Stack.Screen name="Splash" component={SplashScreen} />
        <Stack.Screen name="GetStarted" component={GetStartedScreen} />
        <Stack.Screen name="Login" component={LoginScreen} />
        <Stack.Screen name="Register" component={RegisterScreen} />
        <Stack.Screen name="MainTabs" component={MainTabs} />
        <Stack.Screen name="Schedules" component={ScheduleListScreen} />
        <Stack.Screen name="ScheduleList" component={ScheduleListScreen} />
        <Stack.Screen name="ScheduleDetail" component={ScheduleDetailScreen} />
        <Stack.Screen name="SeatSelection" component={SeatSelectionScreen} />
        <Stack.Screen name="Checkout" component={CheckoutScreen} />
        <Stack.Screen name="TicketDetail" component={TicketDetailScreen} />
        <Stack.Screen name="BookingHistory" component={BookingHistoryScreen} />
        <Stack.Screen name="Charter" component={CharterScreen} />
        <Stack.Screen name="Promo" component={PromoScreen} />
      </Stack.Navigator>
    </NavigationContainer>
  );
}
