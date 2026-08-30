import React from "react";
import { NavigationContainer, LinkingOptions } from "@react-navigation/native";
import { createNativeStackNavigator } from "@react-navigation/native-stack";
import { createBottomTabNavigator } from "@react-navigation/bottom-tabs";
import { Platform } from "react-native";

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
import RewardsScreen from "../screens/RewardsScreen";

export type RootStackParamList = {
  Splash: undefined;
  GetStarted: undefined;
  Login: undefined;
  Register: undefined;
  MainTabs: { screen?: string } | undefined;
  Schedules: { origin?: string; destination?: string } | undefined;
  ScheduleList: { origin?: string; destination?: string } | undefined;
  ScheduleDetail: { scheduleId: number; date?: string };
  SeatSelection: { scheduleId: number; date?: string };
  Checkout: {
    scheduleId: number;
    selectedSeats: number[];
    totalPrice: number;
    schedule?: any;
    date?: string;
  };
  TicketDetail: {
    bookingId?: number;
    booking?: any;
    schedule?: any;
    selectedSeats?: (number | string)[];
  };
  BookingHistory: undefined;
  Charter: undefined;
  Promo: undefined;
  Rewards: undefined;
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

const linking: LinkingOptions<RootStackParamList> = {
  prefixes: [
    Platform.OS === "web" && typeof window !== "undefined" && window?.location
      ? window.location.origin
      : "tunggaljaya://",
    "http://localhost:8081",
    "http://localhost:19006",
    "tunggaljaya://",
  ],
  config: {
    screens: {
      Splash: "splash",
      GetStarted: "get-started",
      Login: "login",
      Register: "register",
      MainTabs: {
        screens: {
          Home: "",
          Schedules: "schedules",
          BookingHistory: "booking-history",
          Help: "help",
          Profile: "profile",
        },
      } as any,
      Schedules: "all-schedules",
      ScheduleList: "schedule-list",
      ScheduleDetail: "schedule/:scheduleId",
      SeatSelection: "seat-selection",
      Checkout: "checkout",
      TicketDetail: "ticket/:bookingId",
      BookingHistory: "my-tickets",
      Charter: "charter",
      Promo: "promo",
      Rewards: "rewards",
      Help: "help-center",
      Profile: "my-profile",
    },
  },
};

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

  // On Web, if user is on specific page, do not force Splash as initial
  const isWeb =
    Platform.OS === "web" &&
    typeof window !== "undefined" &&
    Boolean(window?.location);
  const hasSpecificPath =
    isWeb &&
    window?.location?.pathname !== "/" &&
    window?.location?.pathname !== "/splash";

  const getInitialRoute = (): keyof RootStackParamList => {
    if (hasSpecificPath) {
      return "MainTabs";
    }
    if (user) {
      return "MainTabs";
    }
    return "Splash";
  };

  return (
    <NavigationContainer linking={linking}>
      <Stack.Navigator
        id="root-stack"
        initialRouteName={getInitialRoute()}
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
        <Stack.Screen name="Rewards" component={RewardsScreen} />
        <Stack.Screen name="Help" component={HelpScreen} />
        <Stack.Screen name="Profile" component={ProfileScreen} />
      </Stack.Navigator>
    </NavigationContainer>
  );
}
