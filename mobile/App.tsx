import React, { useEffect } from "react";
import { View, ActivityIndicator, Platform } from "react-native";
import { StatusBar } from "expo-status-bar";
import { SafeAreaProvider } from "react-native-safe-area-context";
import {
  useFonts,
  PlusJakartaSans_400Regular,
  PlusJakartaSans_500Medium,
  PlusJakartaSans_600SemiBold,
  PlusJakartaSans_700Bold,
  PlusJakartaSans_800ExtraBold,
} from "@expo-google-fonts/plus-jakarta-sans";
import { AuthProvider } from "./src/context/AuthContext";
import { RewardProvider } from "./src/context/RewardContext";
import { AlertProvider } from "./src/context/AlertContext";
import RootNavigator from "./src/navigation/RootNavigator";
import { COLORS } from "./src/theme/colors";

// Global web reset to eliminate inner browser focus outline rings
if (Platform.OS === "web" && typeof document !== "undefined") {
  const style = document.createElement("style");
  style.id = "react-native-web-focus-reset";
  style.textContent = `
    input, textarea, select {
      outline: none !important;
      border: none !important;
      box-shadow: none !important;
      background-color: transparent !important;
    }
    input:focus, textarea:focus, select:focus {
      outline: none !important;
      border: none !important;
      box-shadow: none !important;
    }
    * {
      -webkit-tap-highlight-color: transparent !important;
    }
  `;
  document.head.appendChild(style);
}

export default function App() {
  const [fontsLoaded] = useFonts({
    PlusJakartaSans_400Regular,
    PlusJakartaSans_500Medium,
    PlusJakartaSans_600SemiBold,
    PlusJakartaSans_700Bold,
    PlusJakartaSans_800ExtraBold,
  });

  if (!fontsLoaded) {
    return (
      <View
        style={{
          flex: 1,
          backgroundColor: COLORS.bgDark,
          justifyContent: "center",
          alignItems: "center",
        }}
      >
        <ActivityIndicator size="large" color={COLORS.brandRed} />
      </View>
    );
  }

  return (
    <SafeAreaProvider>
      <AlertProvider>
        <AuthProvider>
          <RewardProvider>
            <StatusBar style="dark" backgroundColor="#F4F6F9" />
            <RootNavigator />
          </RewardProvider>
        </AuthProvider>
      </AlertProvider>
    </SafeAreaProvider>
  );
}
