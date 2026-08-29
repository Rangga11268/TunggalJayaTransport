import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  Image,
  StyleSheet,
  Platform,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { Bell, Sparkles } from "lucide-react-native";
import { COLORS } from "../../theme/colors";

interface HomeTopBarProps {
  user: any;
  points: number;
  unreadNotifCount: number;
  onOpenNotif: () => void;
  onOpenProfile: () => void;
  onOpenRewards: () => void;
  onLogin: () => void;
}

export const HomeTopBar: React.FC<HomeTopBarProps> = ({
  user,
  points,
  unreadNotifCount,
  onOpenNotif,
  onOpenProfile,
  onOpenRewards,
  onLogin,
}) => {
  return (
    <SafeAreaView edges={["top"]} style={styles.safeHeader}>
      <View style={styles.headerBar}>
        {/* Brand Logo with Identity Text */}
        <View style={styles.headerBrandLeft}>
          <View style={styles.headerLogoContainer}>
            <Image
              source={require("../../../assets/images/logoNoBg.png")}
              style={styles.headerLogo}
              resizeMode="contain"
            />
          </View>
          <View style={styles.brandTextCol}>
            <Text style={styles.brandTitleText}>PO TUNGGAL JAYA</Text>
            <Text style={styles.brandSubtitleText}>Transport &amp; Pariwisata</Text>
          </View>
        </View>

        {/* Right Action Icons: Points + Notification + Avatar / Login */}
        <View style={styles.headerRight}>
          {user && (
            <TouchableOpacity
              activeOpacity={0.8}
              onPress={onOpenRewards}
              style={styles.pointsPillBtn}
              accessibilityLabel="TJ Rewards Poin"
            >
              <Sparkles size={12} color="#D97706" style={{ marginRight: 3 }} />
              <Text style={styles.pointsPillText}>
                {points.toLocaleString("id-ID")}
              </Text>
            </TouchableOpacity>
          )}

          <TouchableOpacity
            activeOpacity={0.7}
            onPress={onOpenNotif}
            style={styles.iconCircle}
            accessibilityLabel="Notifikasi"
          >
            <Bell size={18} color="#1E293B" />
            {unreadNotifCount > 0 && <View style={styles.badgeDot} />}
          </TouchableOpacity>

          {user ? (
            <TouchableOpacity
              activeOpacity={0.8}
              onPress={onOpenProfile}
              style={styles.avatarRing}
              accessibilityLabel="Profil Saya"
            >
              <Image
                source={require("../../../assets/images/bentas01.webp")}
                style={styles.avatarImg}
              />
            </TouchableOpacity>
          ) : (
            <TouchableOpacity
              activeOpacity={0.85}
              onPress={onLogin}
              style={styles.headerLoginPill}
              accessibilityLabel="Masuk Akun"
            >
              <Text style={styles.headerLoginPillText}>Masuk</Text>
            </TouchableOpacity>
          )}
        </View>
      </View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  safeHeader: {
    backgroundColor: "#FFFFFF",
    zIndex: 50,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.04,
        shadowRadius: 8,
      },
      android: {
        elevation: 2,
      },
      web: {
        boxShadow: "0 2px 8px -2px rgba(15, 23, 42, 0.05)",
      } as any,
    }),
  },
  headerBar: {
    height: 60,
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    paddingHorizontal: 16,
    borderBottomWidth: 1,
    borderBottomColor: "#F1F5F9",
  },
  headerBrandLeft: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
  },
  headerLogoContainer: {
    width: 38,
    height: 38,
    borderRadius: 12,
    backgroundColor: "#F8FAFC",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    justifyContent: "center",
    alignItems: "center",
    overflow: "hidden",
  },
  headerLogo: {
    width: 30,
    height: 30,
  },
  brandTextCol: {
    justifyContent: "center",
  },
  brandTitleText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13.5,
    color: "#0F172A",
    letterSpacing: -0.3,
  },
  brandSubtitleText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 9.5,
    color: COLORS.brandBlue,
    letterSpacing: 0.2,
  },
  headerRight: {
    flexDirection: "row",
    alignItems: "center",
    gap: 8,
  },
  pointsPillBtn: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFBEB",
    borderWidth: 1,
    borderColor: "#FDE68A",
    paddingHorizontal: 9,
    paddingVertical: 5,
    borderRadius: 10,
  },
  pointsPillText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 11,
    color: "#D97706",
  },
  iconCircle: {
    width: 36,
    height: 36,
    borderRadius: 18,
    backgroundColor: "#F8FAFC",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    justifyContent: "center",
    alignItems: "center",
    position: "relative",
  },
  badgeDot: {
    width: 7,
    height: 7,
    borderRadius: 3.5,
    backgroundColor: "#DC2626",
    position: "absolute",
    top: 5,
    right: 6,
    borderWidth: 1.5,
    borderColor: "#FFFFFF",
  },
  avatarRing: {
    width: 36,
    height: 36,
    borderRadius: 18,
    borderWidth: 2,
    borderColor: COLORS.brandBlue,
    overflow: "hidden",
    backgroundColor: "#EFF6FF",
  },
  avatarImg: {
    width: "100%",
    height: "100%",
  },
  headerLoginPill: {
    backgroundColor: COLORS.brandBlue,
    paddingHorizontal: 14,
    paddingVertical: 7,
    borderRadius: 10,
    justifyContent: "center",
    alignItems: "center",
  },
  headerLoginPillText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#FFFFFF",
  },
});
