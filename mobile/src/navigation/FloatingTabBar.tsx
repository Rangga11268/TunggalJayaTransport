import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  Platform,
} from "react-native";
import { BottomTabBarProps } from "@react-navigation/bottom-tabs";
import { Home, Calendar, Ticket, Headphones, User } from "lucide-react-native";
import { COLORS } from "../theme/colors";

interface TabConfig {
  label: string;
  icon: any;
  isCenter?: boolean;
}

const TAB_CONFIGS: Record<string, TabConfig> = {
  Home: {
    label: "Beranda",
    icon: Home,
  },
  Schedules: {
    label: "Jadwal",
    icon: Calendar,
  },
  BookingHistory: {
    label: "Tiket Saya",
    icon: Ticket,
    isCenter: true,
  },
  Help: {
    label: "Bantuan",
    icon: Headphones,
  },
  Profile: {
    label: "Akun",
    icon: User,
  },
};

export default function FloatingTabBar({
  state,
  descriptors,
  navigation,
}: BottomTabBarProps) {
  return (
    <View style={styles.floatingWrapper} pointerEvents="box-none">
      <View style={styles.container}>
        {state.routes.map((route, index) => {
          const isFocused = state.index === index;
          const config = TAB_CONFIGS[route.name] || {
            label: route.name,
            icon: Home,
          };
          const IconComp = config.icon;
          const isCenter = config.isCenter || index === 2;

          const onPress = () => {
            const event = navigation.emit({
              type: "tabPress",
              target: route.key,
              canPreventDefault: true,
            });

            if (!isFocused && !event.defaultPrevented) {
              navigation.navigate(route.name);
            }
          };

          // 1. ELEVATED HERO CENTER BUTTON
          if (isCenter) {
            return (
              <TouchableOpacity
                key={route.key}
                activeOpacity={0.85}
                accessibilityRole="button"
                accessibilityLabel={config.label}
                accessibilityState={isFocused ? { selected: true } : {}}
                onPress={onPress}
                style={styles.centerHeroWrapper}
              >
                <View
                  style={[
                    styles.centerHeroButton,
                    isFocused
                      ? styles.centerHeroButtonActive
                      : styles.centerHeroButtonInactive,
                  ]}
                >
                  <IconComp size={22} color="#FFFFFF" strokeWidth={2.4} />
                </View>
                <Text
                  style={[
                    styles.centerHeroLabel,
                    isFocused && styles.centerHeroLabelActive,
                  ]}
                  numberOfLines={1}
                >
                  {config.label}
                </Text>
              </TouchableOpacity>
            );
          }

          // 2. STANDARD SIDE TABS
          return (
            <TouchableOpacity
              key={route.key}
              activeOpacity={0.75}
              accessibilityRole="button"
              accessibilityLabel={config.label}
              accessibilityState={isFocused ? { selected: true } : {}}
              onPress={onPress}
              style={styles.tabItem}
            >
              <View
                style={[
                  styles.iconWrapper,
                  isFocused && styles.iconWrapperActive,
                ]}
              >
                <IconComp
                  size={19}
                  color={isFocused ? COLORS.brandBlue : "#64748B"}
                  strokeWidth={isFocused ? 2.3 : 1.8}
                />
              </View>

              <Text
                style={[
                  styles.tabLabel,
                  isFocused ? styles.tabLabelActive : styles.tabLabelInactive,
                ]}
                numberOfLines={1}
              >
                {config.label}
              </Text>
            </TouchableOpacity>
          );
        })}
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  floatingWrapper: {
    position: "absolute",
    bottom: Platform.OS === "ios" ? 22 : 12,
    left: 0,
    right: 0,
    alignItems: "center",
    justifyContent: "center",
    zIndex: 99,
  },
  container: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    backgroundColor: "#FFFFFF",
    paddingVertical: 6,
    paddingHorizontal: 8,
    borderRadius: 30,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    width: "92%",
    maxWidth: 400,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 8 },
        shadowOpacity: 0.1,
        shadowRadius: 18,
      },
      android: {
        elevation: 8,
      },
      web: {
        boxShadow: "0px 8px 24px rgba(15, 23, 42, 0.12)",
      } as any,
    }),
  },
  tabItem: {
    flex: 1,
    alignItems: "center",
    justifyContent: "center",
    paddingVertical: 2,
  },
  iconWrapper: {
    width: 36,
    height: 28,
    borderRadius: 14,
    justifyContent: "center",
    alignItems: "center",
  },
  iconWrapperActive: {
    backgroundColor: "rgba(37, 99, 235, 0.1)",
  },
  tabLabel: {
    fontSize: 10,
    marginTop: 2,
    textAlign: "center",
  },
  tabLabelActive: {
    fontFamily: "PlusJakartaSans_700Bold",
    color: COLORS.brandBlue,
  },
  tabLabelInactive: {
    fontFamily: "PlusJakartaSans_500Medium",
    color: "#64748B",
  },

  /* HERO CENTER BUTTON STYLES */
  centerHeroWrapper: {
    flex: 1.15,
    alignItems: "center",
    justifyContent: "center",
    marginTop: -22,
  },
  centerHeroButton: {
    width: 50,
    height: 50,
    borderRadius: 25,
    justifyContent: "center",
    alignItems: "center",
    borderWidth: 3,
    borderColor: "#FFFFFF",
    ...Platform.select({
      ios: {
        shadowColor: "#2563EB",
        shadowOffset: { width: 0, height: 6 },
        shadowOpacity: 0.38,
        shadowRadius: 10,
      },
      android: {
        elevation: 8,
      },
      web: {
        boxShadow: "0px 6px 16px rgba(37, 99, 235, 0.4)",
      } as any,
    }),
  },
  centerHeroButtonActive: {
    backgroundColor: COLORS.brandBlue,
  },
  centerHeroButtonInactive: {
    backgroundColor: "#1E293B",
  },
  centerHeroLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10,
    color: "#64748B",
    marginTop: 3,
    textAlign: "center",
  },
  centerHeroLabelActive: {
    color: COLORS.brandBlue,
    fontFamily: "PlusJakartaSans_800ExtraBold",
  },
});
