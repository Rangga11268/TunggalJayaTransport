import React, { useEffect, useRef } from "react";
import {
  View,
  Text,
  StyleSheet,
  Image,
  Animated,
  Dimensions,
  Easing,
  StatusBar,
  Platform,
} from "react-native";
import { LinearGradient } from "expo-linear-gradient";
import { useNavigation } from "@react-navigation/native";
import { NativeStackNavigationProp } from "@react-navigation/native-stack";
import { RootStackParamList } from "../navigation/RootNavigator";
import { useAuth } from "../context/AuthContext";
import { COLORS } from "../theme/colors";

const { width, height } = Dimensions.get("window");
type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

export default function SplashScreen() {
  const navigation = useNavigation<NavigationProp>();
  const { user, isLoading } = useAuth();

  // Animation values
  const logoScale = useRef(new Animated.Value(0.7)).current;
  const logoOpacity = useRef(new Animated.Value(0)).current;
  const textOpacity = useRef(new Animated.Value(0)).current;
  const textTranslateY = useRef(new Animated.Value(15)).current;
  const pulseAnim = useRef(new Animated.Value(1)).current;
  const progressWidth = useRef(new Animated.Value(0)).current;

  useEffect(() => {
    // 1. Logo Entrance Animation (Spring Zoom & Fade)
    Animated.parallel([
      Animated.spring(logoScale, {
        toValue: 1,
        friction: 6,
        tension: 40,
        useNativeDriver: true,
      }),
      Animated.timing(logoOpacity, {
        toValue: 1,
        duration: 700,
        easing: Easing.out(Easing.ease),
        useNativeDriver: true,
      }),
    ]).start();

    // 2. Tagline Entrance Animation
    Animated.parallel([
      Animated.timing(textOpacity, {
        toValue: 1,
        duration: 600,
        delay: 350,
        useNativeDriver: true,
      }),
      Animated.timing(textTranslateY, {
        toValue: 0,
        duration: 600,
        delay: 350,
        easing: Easing.out(Easing.back(1.5)),
        useNativeDriver: true,
      }),
    ]).start();

    // 3. Subtle Pulsing Glow Animation
    Animated.loop(
      Animated.sequence([
        Animated.timing(pulseAnim, {
          toValue: 1.06,
          duration: 1000,
          easing: Easing.inOut(Easing.ease),
          useNativeDriver: true,
        }),
        Animated.timing(pulseAnim, {
          toValue: 1,
          duration: 1000,
          easing: Easing.inOut(Easing.ease),
          useNativeDriver: true,
        }),
      ]),
    ).start();

    // 4. Progress bar loading animation
    Animated.timing(progressWidth, {
      toValue: 1,
      duration: 1600,
      easing: Easing.inOut(Easing.quad),
      useNativeDriver: false,
    }).start();

    // 5. Navigate after smooth splash sequence
    const timer = setTimeout(() => {
      if (user) {
        navigation.replace("MainTabs");
      } else {
        navigation.replace("GetStarted");
      }
    }, 1800);

    return () => clearTimeout(timer);
  }, [user, isLoading]);

  const progressInterpolate = progressWidth.interpolate({
    inputRange: [0, 1],
    outputRange: ["0%", "100%"],
  });

  return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" backgroundColor="#0B132B" />

      {/* Dark Luxury Gradient Background */}
      <LinearGradient
        colors={["#0B132B", "#10207A", "#070D1F"]}
        start={{ x: 0.1, y: 0.1 }}
        end={{ x: 0.9, y: 0.9 }}
        style={StyleSheet.absoluteFillObject}
      />

      {/* Subtle Glow Circle */}
      <Animated.View
        style={[
          styles.glowCircle,
          {
            transform: [{ scale: pulseAnim }],
          },
        ]}
      />

      {/* Center Brand Elements */}
      <View style={styles.centerContent}>
        {/* Animated Brand Logo */}
        <Animated.View
          style={[
            styles.logoContainer,
            {
              opacity: logoOpacity,
              transform: [{ scale: logoScale }],
            },
          ]}
        >
          <View style={styles.logoBackdrop}>
            <Image
              source={require("../../assets/images/logoNoBg.png")}
              style={styles.logoImage}
              resizeMode="contain"
            />
          </View>
        </Animated.View>

        {/* Animated Brand Title & Tagline */}
        <Animated.View
          style={[
            styles.titleContainer,
            {
              opacity: textOpacity,
              transform: [{ translateY: textTranslateY }],
            },
          ]}
        >
          <Text style={styles.brandTitle}>PO Tunggal Jaya</Text>
          <Text style={styles.brandSubtitle}>
            Executive &amp; Tourism Bus Transport
          </Text>
        </Animated.View>
      </View>

      {/* Bottom Loading Progress Strip */}
      <View style={styles.bottomContainer}>
        <View style={styles.progressBarTrack}>
          <Animated.View
            style={[
              styles.progressBarFill,
              {
                width: progressInterpolate,
              },
            ]}
          />
        </View>
        <Text style={styles.versionText}>
          Versi 2.4.0 • Official PO Tunggal Jaya
        </Text>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#0B132B",
    justifyContent: "space-between",
    alignItems: "center",
    paddingVertical: 50,
  },
  glowCircle: {
    position: "absolute",
    top: height * 0.32,
    width: 220,
    height: 220,
    borderRadius: 110,
    backgroundColor: "rgba(37, 99, 235, 0.18)",
  },
  centerContent: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    paddingHorizontal: 24,
  },
  logoContainer: {
    marginBottom: 20,
    alignItems: "center",
  },
  logoBackdrop: {
    width: 140,
    height: 140,
    borderRadius: 36,
    backgroundColor: "#FFFFFF",
    justifyContent: "center",
    alignItems: "center",
    padding: 12,
    ...Platform.select({
      ios: {
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 10 },
        shadowOpacity: 0.3,
        shadowRadius: 20,
      },
      android: {
        elevation: 12,
      },
    }),
  },
  logoImage: {
    width: "100%",
    height: "100%",
  },
  titleContainer: {
    alignItems: "center",
  },
  brandTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 24,
    color: "#FFFFFF",
    letterSpacing: -0.5,
    marginBottom: 4,
  },
  brandSubtitle: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 13,
    color: "#93C5FD",
    letterSpacing: 0.5,
  },
  bottomContainer: {
    width: "100%",
    alignItems: "center",
    paddingHorizontal: 40,
  },
  progressBarTrack: {
    width: 140,
    height: 4,
    borderRadius: 2,
    backgroundColor: "rgba(255, 255, 255, 0.15)",
    overflow: "hidden",
    marginBottom: 12,
  },
  progressBarFill: {
    height: "100%",
    backgroundColor: "#38BDF8",
    borderRadius: 2,
  },
  versionText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "rgba(255, 255, 255, 0.5)",
    letterSpacing: 0.3,
  },
});
