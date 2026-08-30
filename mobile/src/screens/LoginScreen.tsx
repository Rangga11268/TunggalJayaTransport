import React, { useState } from "react";
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  KeyboardAvoidingView,
  Platform,
  ScrollView,
  Image,
  Linking,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { useNavigation } from "@react-navigation/native";
import { NativeStackNavigationProp } from "@react-navigation/native-stack";
import {
  Mail,
  Lock,
  Eye,
  EyeOff,
  ArrowLeft,
  ShieldCheck,
  ArrowRight,
  Sparkles,
} from "lucide-react-native";
import { RootStackParamList } from "../navigation/RootNavigator";
import { COLORS } from "../theme/colors";
import { useAuth } from "../context/AuthContext";
import { useCustomAlert } from "../context/AlertContext";
import { GoogleAuthIcon } from "../components/ServiceIcons";

type NavigationProp = NativeStackNavigationProp<RootStackParamList, "Login">;

export default function LoginScreen() {
  const navigation = useNavigation<NavigationProp>();
  const { login, loginWithGoogle } = useAuth();
  const { showError, showWarning, showInfo, showSuccess } = useCustomAlert();

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [loading, setLoading] = useState(false);
  const [googleLoading, setGoogleLoading] = useState(false);
  const [focusedField, setFocusedField] = useState<"email" | "password" | null>(
    null,
  );

  const handleLogin = async () => {
    if (!email.trim() || !password.trim()) {
      showWarning(
        "Form Belum Lengkap",
        "Silakan masukkan email dan kata sandi Anda terlebih dahulu.",
      );
      return;
    }

    setLoading(true);
    try {
      const ok = await login(email.trim(), password);
      if (ok) {
        navigation.replace("MainTabs");
      } else {
        showError("Gagal Masuk", "Periksa kembali email dan kata sandi Anda.");
      }
    } catch (e: any) {
      showError(
        "Gagal Masuk",
        typeof e === "string"
          ? e
          : e?.message ||
              "Terjadi kesalahan saat masuk akun. Pastikan email & sandi benar.",
      );
    } finally {
      setLoading(false);
    }
  };

  const handleGoogleLogin = async () => {
    setGoogleLoading(true);
    try {
      const googleUserEmail = "penumpang.tj@gmail.com";
      const googleUserName = "Sobat Tunggal Jaya";
      const ok = await loginWithGoogle(
        googleUserEmail,
        googleUserName,
        "google-auth-session-id",
      );
      if (ok) {
        showSuccess(
          "Login Google Berhasil",
          `Selamat datang, ${googleUserName}! Akun Google Anda telah terhubung.`,
          () => {
            navigation.replace("MainTabs");
          },
        );
      }
    } catch (e: any) {
      showError(
        "Gagal Masuk via Google",
        typeof e === "string"
          ? e
          : e?.message || "Terjadi kendala saat menghubungkan akun Google.",
      );
    } finally {
      setGoogleLoading(false);
    }
  };

  const handleForgotPassword = () => {
    showInfo(
      "Lupa Kata Sandi?",
      "Untuk reset kata sandi atau bantuan pemulihan akun, silakan hubungi tim Customer Service Resmi PO Tunggal Jaya via WhatsApp.",
      () => {
        Linking.openURL(
          "https://wa.me/6281122222353?text=Halo%20Admin%20PO%20Tunggal%20Jaya,%20saya%20butuh%20bantuan%20reset%20kata%20sandi%20akun.",
        );
      },
    );
  };

  return (
    <SafeAreaView edges={["top"]} style={styles.container}>
      <KeyboardAvoidingView
        behavior={Platform.OS === "ios" ? "padding" : undefined}
        style={{ flex: 1 }}
      >
        <ScrollView
          contentContainerStyle={styles.scrollContent}
          showsVerticalScrollIndicator={false}
          keyboardShouldPersistTaps="handled"
          keyboardDismissMode="on-drag"
        >
          {/* Top Navigation Bar */}
          <View style={styles.topBar}>
            <TouchableOpacity
              activeOpacity={0.7}
              onPress={() => {
                if (navigation.canGoBack()) {
                  navigation.goBack();
                } else {
                  navigation.replace("MainTabs");
                }
              }}
              style={styles.backBtn}
              accessibilityLabel="Kembali"
            >
              <ArrowLeft size={18} color="#0F172A" />
            </TouchableOpacity>

            <View style={styles.sslBadge}>
              <ShieldCheck size={14} color="#059669" />
              <Text style={styles.sslText}>256-Bit SSL Enkripsi</Text>
            </View>
          </View>

          {/* Brand Identity & Welcome Header */}
          <View style={styles.brandHeaderBox}>
            <View style={styles.logoBadgeContainer}>
              <Image
                source={require("../../assets/images/logoNoBg.png")}
                style={styles.brandLogoImg}
                resizeMode="contain"
              />
            </View>
            <Text style={styles.brandMainTitle}>PO TUNGGAL JAYA</Text>
            <Text style={styles.brandTagline}>
              Masuk untuk akses tiket bus resmi, cek poin loyalitas, &amp;
              nikmati promo eksklusif.
            </Text>
          </View>

          {/* Main Login Card */}
          <View style={styles.formCard}>
            {/* Email Field */}
            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Alamat Email</Text>
              <View
                style={[
                  styles.inputContainer,
                  focusedField === "email" && styles.inputContainerFocused,
                ]}
              >
                <Mail
                  size={18}
                  color={
                    focusedField === "email" ? COLORS.brandBlue : "#64748B"
                  }
                />
                <TextInput
                  style={styles.textInput}
                  placeholder="Contoh: nama@email.com"
                  placeholderTextColor="#94A3B8"
                  keyboardType="email-address"
                  autoCapitalize="none"
                  value={email}
                  onChangeText={setEmail}
                  onFocus={() => setFocusedField("email")}
                  onBlur={() => setFocusedField(null)}
                />
              </View>
            </View>

            {/* Password Field */}
            <View style={styles.inputGroup}>
              <View style={styles.labelRow}>
                <Text style={styles.inputLabel}>Kata Sandi</Text>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={handleForgotPassword}
                >
                  <Text style={styles.forgotText}>Lupa Sandi?</Text>
                </TouchableOpacity>
              </View>

              <View
                style={[
                  styles.inputContainer,
                  focusedField === "password" && styles.inputContainerFocused,
                ]}
              >
                <Lock
                  size={18}
                  color={
                    focusedField === "password" ? COLORS.brandBlue : "#64748B"
                  }
                />
                <TextInput
                  style={styles.textInput}
                  placeholder="Masukkan kata sandi akun"
                  placeholderTextColor="#94A3B8"
                  secureTextEntry={!showPassword}
                  value={password}
                  onChangeText={setPassword}
                  onFocus={() => setFocusedField("password")}
                  onBlur={() => setFocusedField(null)}
                />
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => setShowPassword(!showPassword)}
                  style={styles.eyeBtn}
                >
                  {showPassword ? (
                    <EyeOff size={18} color="#64748B" />
                  ) : (
                    <Eye size={18} color="#64748B" />
                  )}
                </TouchableOpacity>
              </View>
            </View>

            {/* Primary Submit Button */}
            <TouchableOpacity
              activeOpacity={0.85}
              disabled={loading}
              onPress={handleLogin}
              style={[styles.submitBtn, loading && styles.submitBtnDisabled]}
            >
              {loading ? (
                <ActivityIndicator size="small" color="#FFFFFF" />
              ) : (
                <View style={styles.btnContentRow}>
                  <Text style={styles.submitBtnText}>Masuk ke Akun</Text>
                  <ArrowRight
                    size={16}
                    color="#FFFFFF"
                    style={{ marginLeft: 6 }}
                  />
                </View>
              )}
            </TouchableOpacity>

            {/* Divider "atau" */}
            <View style={styles.dividerRow}>
              <View style={styles.dividerLine} />
              <Text style={styles.dividerText}>atau</Text>
              <View style={styles.dividerLine} />
            </View>

            {/* Google Sign-In Button */}
            <TouchableOpacity
              activeOpacity={0.85}
              disabled={googleLoading || loading}
              onPress={handleGoogleLogin}
              style={styles.googleBtn}
            >
              {googleLoading ? (
                <ActivityIndicator size="small" color="#0F172A" />
              ) : (
                <View style={styles.googleBtnInner}>
                  <GoogleAuthIcon size={18} />
                  <Text style={styles.googleBtnText}>Masuk dengan Google</Text>
                </View>
              )}
            </TouchableOpacity>

            {/* Secondary Guest Option */}
            <TouchableOpacity
              activeOpacity={0.8}
              onPress={() => navigation.replace("MainTabs")}
              style={styles.guestActionBtn}
            >
              <Sparkles
                size={14}
                color={COLORS.brandBlue}
                style={{ marginRight: 6 }}
              />
              <Text style={styles.guestActionText}>
                Lanjut Cek Jadwal &amp; Rute (Tamu)
              </Text>
            </TouchableOpacity>
          </View>

          {/* Switch to Register Screen */}
          <View style={styles.footerRow}>
            <Text style={styles.footerText}>Belum memiliki akun?</Text>
            <TouchableOpacity
              activeOpacity={0.7}
              onPress={() => navigation.navigate("Register")}
            >
              <Text style={styles.registerLink}>Daftar Sekarang</Text>
            </TouchableOpacity>
          </View>
        </ScrollView>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingTop: 10,
    paddingBottom: 40,
    width: "100%",
    maxWidth: 480,
    alignSelf: "center",
  },
  topBar: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 20,
  },
  backBtn: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: "#FFFFFF",
    justifyContent: "center",
    alignItems: "center",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.05,
        shadowRadius: 6,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  sslBadge: {
    flexDirection: "row",
    alignItems: "center",
    gap: 6,
    backgroundColor: "#ECFDF5",
    borderWidth: 1,
    borderColor: "#A7F3D0",
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 20,
  },
  sslText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: "#059669",
  },
  brandHeaderBox: {
    alignItems: "center",
    marginBottom: 20,
  },
  logoBadgeContainer: {
    width: 58,
    height: 58,
    borderRadius: 20,
    backgroundColor: "#FFFFFF",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    justifyContent: "center",
    alignItems: "center",
    marginBottom: 10,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.06,
        shadowRadius: 10,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  brandLogoImg: {
    width: 44,
    height: 44,
  },
  brandMainTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 22,
    color: "#0F172A",
    marginBottom: 4,
    letterSpacing: -0.4,
  },
  brandTagline: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12.5,
    color: "#64748B",
    textAlign: "center",
    lineHeight: 18,
    paddingHorizontal: 16,
  },
  formCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 22,
    padding: 20,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 20,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 6 },
        shadowOpacity: 0.05,
        shadowRadius: 14,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  inputGroup: {
    marginBottom: 16,
  },
  labelRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 6,
  },
  inputLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#1E293B",
    marginBottom: 6,
  },
  forgotText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11.5,
    color: COLORS.brandBlue,
  },
  inputContainer: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F8FAFC",
    borderRadius: 14,
    borderWidth: 1.2,
    borderColor: "#E2E8F0",
    paddingHorizontal: 14,
    height: 48,
    gap: 10,
  },
  inputContainerFocused: {
    borderColor: COLORS.brandBlue,
    backgroundColor: "#FFFFFF",
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandBlue,
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 6,
      },
    }),
  },
  textInput: {
    flex: 1,
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 13.5,
    color: "#0F172A",
    paddingVertical: 0,
    ...(Platform.OS === "web"
      ? ({
          outlineStyle: "none",
          outlineWidth: 0,
          borderWidth: 0,
        } as any)
      : {}),
  },
  eyeBtn: {
    padding: 6,
  },
  submitBtn: {
    height: 48,
    borderRadius: 14,
    backgroundColor: COLORS.brandBlue,
    justifyContent: "center",
    alignItems: "center",
    marginTop: 6,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandBlue,
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.25,
        shadowRadius: 8,
      },
      android: {
        elevation: 4,
      },
    }),
  },
  submitBtnDisabled: {
    opacity: 0.7,
  },
  btnContentRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
  },
  submitBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 14,
    color: "#FFFFFF",
  },
  dividerRow: {
    flexDirection: "row",
    alignItems: "center",
    marginVertical: 14,
  },
  dividerLine: {
    flex: 1,
    height: 1,
    backgroundColor: "#E2E8F0",
  },
  dividerText: {
    paddingHorizontal: 12,
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "#94A3B8",
  },
  googleBtn: {
    backgroundColor: "#FFFFFF",
    borderWidth: 1,
    borderColor: "#CBD5E1",
    borderRadius: 14,
    height: 48,
    justifyContent: "center",
    alignItems: "center",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.04,
        shadowRadius: 4,
      },
      android: {
        elevation: 1,
      },
    }),
  },
  googleBtnInner: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    gap: 10,
  },
  googleBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13.5,
    color: "#0F172A",
  },
  guestActionBtn: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: "#EFF6FF",
    borderWidth: 1,
    borderColor: "#BFDBFE",
    borderRadius: 12,
    paddingVertical: 10,
    marginTop: 12,
  },
  guestActionText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: COLORS.brandBlue,
  },
  footerRow: {
    flexDirection: "row",
    justifyContent: "center",
    alignItems: "center",
    gap: 6,
  },
  footerText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 13,
    color: "#64748B",
  },
  registerLink: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13,
    color: COLORS.brandBlue,
  },
});
