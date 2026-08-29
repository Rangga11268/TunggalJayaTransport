import React from "react";
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  Platform,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { useNavigation } from "@react-navigation/native";
import { ArrowLeft } from "lucide-react-native";

interface ScreenHeaderProps {
  title: string;
  subtitle?: string;
  showBack?: boolean;
  onBack?: () => void;
  leftElement?: React.ReactNode;
  rightElement?: React.ReactNode;
  backgroundColor?: string;
  borderBottom?: boolean;
}

export const ScreenHeader: React.FC<ScreenHeaderProps> = ({
  title,
  subtitle,
  showBack = true,
  onBack,
  leftElement,
  rightElement,
  backgroundColor = "#FFFFFF",
  borderBottom = true,
}) => {
  const navigation = useNavigation();

  const handleBack = () => {
    if (onBack) {
      onBack();
    } else if (navigation.canGoBack()) {
      navigation.goBack();
    } else {
      (navigation as any).navigate("MainTabs");
    }
  };

  return (
    <SafeAreaView
      edges={["top"]}
      style={[
        styles.safeArea,
        { backgroundColor },
        borderBottom && styles.bottomBorder,
      ]}
    >
      <View style={styles.container}>
        {/* Left Action / Back Button */}
        <View style={styles.leftCol}>
          {leftElement ? (
            leftElement
          ) : showBack ? (
            <TouchableOpacity
              activeOpacity={0.7}
              onPress={handleBack}
              style={styles.circleBtn}
              accessibilityLabel="Kembali"
            >
              <ArrowLeft size={18} color="#111827" />
            </TouchableOpacity>
          ) : null}
        </View>

        {/* Center / Text Title & Subtitle */}
        <View style={styles.textCol}>
          <Text style={styles.title} numberOfLines={1} ellipsizeMode="tail">
            {title}
          </Text>
          {subtitle ? (
            <Text
              style={styles.subtitle}
              numberOfLines={1}
              ellipsizeMode="tail"
            >
              {subtitle}
            </Text>
          ) : null}
        </View>

        {/* Right Action Element */}
        <View style={styles.rightCol}>
          {rightElement ? rightElement : <View style={styles.rightSpacer} />}
        </View>
      </View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  safeArea: {
    zIndex: 50,
  },
  bottomBorder: {
    borderBottomWidth: 1,
    borderBottomColor: "#E2E8F0",
  },
  container: {
    minHeight: 56,
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    paddingHorizontal: 16,
    paddingVertical: 6,
    gap: 8,
  },
  leftCol: {
    flexShrink: 0,
    justifyContent: "center",
    alignItems: "flex-start",
  },
  textCol: {
    flex: 1,
    justifyContent: "center",
    alignItems: "flex-start",
    paddingHorizontal: 4,
  },
  rightCol: {
    flexShrink: 0,
    justifyContent: "center",
    alignItems: "flex-end",
  },
  rightSpacer: {
    width: 8,
    height: 38,
  },
  circleBtn: {
    width: 38,
    height: 38,
    borderRadius: 19,
    backgroundColor: "#F8FAFC",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    justifyContent: "center",
    alignItems: "center",
  },
  title: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#0F172A",
    letterSpacing: -0.2,
  },
  subtitle: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "#64748B",
    marginTop: 1,
  },
});
