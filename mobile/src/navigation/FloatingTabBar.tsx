import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  Platform,
} from "react-native";
import { BottomTabBarProps } from "@react-navigation/bottom-tabs";
import { Home, Calendar, HelpCircle, User } from "lucide-react-native";
import { COLORS } from "../theme/colors";
import { NavTicketIcon } from "../components/ServiceIcons";

interface TabConfig {
  label: string;
  icon: any;
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
    label: "Pesanan",
    icon: NavTicketIcon,
  },
  Help: {
    label: "Bantuan",
    icon: HelpCircle,
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
          const activeColor = COLORS.brandBlue;
          const inactiveColor = "#64748B";

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

          return (
            <TouchableOpacity
              key={route.key}
              activeOpacity={0.75}
              accessibilityRole="button"
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
                  size={20}
                  color={isFocused ? activeColor : inactiveColor}
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
    bottom: Platform.OS === "ios" ? 24 : 14,
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
    paddingVertical: 7,
    paddingHorizontal: 8,
    borderRadius: 32,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    width: "92%",
    maxWidth: 390,
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
        boxShadow: "0px 8px 24px rgba(15, 23, 42, 0.1)",
      },
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
    fontSize: 10.5,
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
});
