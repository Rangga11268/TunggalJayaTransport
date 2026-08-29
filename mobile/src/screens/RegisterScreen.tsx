import React, { useState } from "react";
import {
  View,
  Text,
  StyleSheet,
  TextInput,
  TouchableOpacity,
  ScrollView,
  ActivityIndicator,
  KeyboardAvoidingView,
  Platform,
  Image,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { useNavigation } from "@react-navigation/native";
import { NativeStackNavigationProp } from "@react-navigation/native-stack";
import {
  User,
  Mail,
  Lock,
  Phone,
  ArrowLeft,
  ShieldCheck,
  Eye,
  EyeOff,
  ArrowRight,
} from "lucide-react-native";
import { RootStackParamList } from "../navigation/RootNavigator";
import { COLORS } from "../theme/colors";
import { useAuth } from "../context/AuthContext";
import { useCustomAlert } from "../context/AlertContext";
import { GoogleAuthIcon } from "../components/ServiceIcons";

type NavigationProp = NativeStackNavigationProp<RootStackParamList, "Register">;

export default function RegisterScreen() {
  const navigation = useNavigation<NavigationProp>();
  const { register, loginWithGoogle } = useAuth();
  const { showError, showWarning, showSuccess } = useCustomAlert();

  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [password, setPassword] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [loading, setLoading] = useState(false);
  const [googleLoading, setGoogleLoading] = useState(false);
  const [focusedField, setFocusedField] = useState<string | null>(null);

  const handleRegister = async () => {
    if (!name.trim() || !email.trim() || !phone.trim() || !password) {
      showWarning(
        "Form Belum Lengkap",
        "Harap lengkapi seluruh kolom formulir pendaftaran.",
      );
      return;
    }

    if (password.length < 8) {
      showWarning(
        "Kata Sandi Terlalu Pendek",
        "Kata sandi minimal terdiri dari 8 karakter demi keamanan akun Anda.",
      );
      return;
    }

    try {
      setLoading(true);
      const ok = await register(
        name.trim(),
        email.trim(),
        password,
        phone.trim(),
      );
      if (ok) {
        showSuccess(
          "Pendaftaran Berhasil",
          "Selamat datang di PO Tunggal Jaya! Akun Anda telah aktif.",
          () => {
            navigation.replace("MainTabs");
          },
        );
      } else {
        showError(
          "Pendaftaran Gagal",
          "Periksa kembali format data yang Anda masukkan.",
        );
      }
    } catch (err: any) {
      showError(
        "Gagal Mendaftar",
        typeof err === "string"
          ? err
          : err?.message || "Terjadi kesalahan saat pendaftaran akun.",
      );
    } finally {
      setLoading(false);
    }
  };

  const handleGoogleRegister = async () => {
    setGoogleLoading(true);
    try {
      const googleUserEmail = "penumpang.tj@gmail.com";
      const googleUserName = name.trim() || "Sobat Tunggal Jaya";
      const ok = await loginWithGoogle(
        googleUserEmail,
        googleUserName,
        "google-auth-reg-session",
      );
      if (ok) {
        showSuccess(
          "Pendaftaran Google Berhasil",
          `Selamat datang, ${googleUserName}! Akun Google Anda telah terhubung.`,
          () => {
            navigation.replace("MainTabs");
          },
        );
      }
    } catch (e: any) {
      showError(
        "Gagal Mendaftar via Google",
        typeof e === "string"
          ? e
          : e?.message || "Terjadi kendala saat menghubungkan akun Google.",
      );
    } finally {
      setGoogleLoading(false);
    }
  };

  return (
    <SafeAreaView edges={["top", "bottom"]} style={styles.container}>
      <KeyboardAvoidingView
        behavior={Platform.OS === "ios" ? "padding" : undefined}
        style={{ flex: 1 }}
      >
        <ScrollView
          contentContainerStyle={styles.scrollContent}
          showsVerticalScrollIndicator={false}
          keyboardShouldPersistTaps="handled"
        >
          {/* Top Bar */}
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
              <Text style={styles.sslText}>Enkripsi Aman SSL</Text>
            </View>
          </View>

          {/* Brand Identity & Form Header */}
          <View style={styles.brandHeaderBox}>
            <View style={styles.logoBadgeContainer}>
              <Image
                source={require("../../assets/images/logoNoBg.png")}
                style={styles.brandLogoImg}
                resizeMode="contain"
              />
            </View>
            <Text style={styles.brandMainTitle}>Buat Akun Member</Text>
            <Text style={styles.brandTagline}>
              Daftar sekarang untuk kemudahan pemesanan tiket, riwayat
              perjalanan, dan pengumpulan TJ Poin rewards.
            </Text>
          </View>

          {/* Main Form Card */}
          <View style={styles.formCard}>
            {/* Full Name */}
            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Nama Lengkap (Sesuai KTP)</Text>
              <View
                style={[
                  styles.inputContainer,
                  focusedField === "name" && styles.inputContainerFocused,
                ]}
              >
                <User
                  size={18}
                  color={focusedField === "name" ? COLORS.brandBlue : "#64748B"}
                />
                <TextInput
                  style={styles.textInput}
                  placeholder="Contoh: Ahmad Pratama"
                  placeholderTextColor="#94A3B8"
                  value={name}
                  onChangeText={setName}
                  onFocus={() => setFocusedField("name")}
                  onBlur={() => setFocusedField(null)}
                />
              </View>
            </View>

            {/* Email Address */}
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
                  placeholder="nama@email.com"
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

            {/* Phone Number */}
            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Nomor WhatsApp / HP</Text>
              <View
                style={[
                  styles.inputContainer,
                  focusedField === "phone" && styles.inputContainerFocused,
                ]}
              >
                <Phone
                  size={18}
                  color={
                    focusedField === "phone" ? COLORS.brandBlue : "#64748B"
                  }
                />
                <TextInput
                  style={styles.textInput}
                  placeholder="081234567890"
                  placeholderTextColor="#94A3B8"
                  keyboardType="phone-pad"
                  value={phone}
                  onChangeText={setPhone}
                  onFocus={() => setFocusedField("phone")}
                  onBlur={() => setFocusedField(null)}
                />
              </View>
            </View>

            {/* Password */}
            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>
                Kata Sandi (Minimal 8 Karakter)
              </Text>
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
                  placeholder="Masukkan kata sandi aman"
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

            {/* Submit Button */}
            <TouchableOpacity
              activeOpacity={0.85}
              disabled={loading}
              onPress={handleRegister}
              style={[styles.submitBtn, loading && styles.submitBtnDisabled]}
            >
              {loading ? (
                <ActivityIndicator size="small" color="#FFFFFF" />
              ) : (
                <View style={styles.btnContentRow}>
                  <Text style={styles.submitBtnText}>Daftar Sekarang</Text>
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

            {/* Google Sign-Up Button */}
            <TouchableOpacity
              activeOpacity={0.85}
              disabled={googleLoading || loading}
              onPress={handleGoogleRegister}
              style={styles.googleBtn}
            >
              {googleLoading ? (
                <ActivityIndicator size="small" color="#0F172A" />
              ) : (
                <View style={styles.googleBtnInner}>
                  <GoogleAuthIcon size={18} />
                  <Text style={styles.googleBtnText}>Daftar dengan Google</Text>
                </View>
              )}
            </TouchableOpacity>
          </View>

          {/* Bottom Switch to Login */}
          <View style={styles.footerRow}>
            <Text style={styles.footerText}>Sudah memiliki akun?</Text>
            <TouchableOpacity
              activeOpacity={0.7}
              onPress={() => navigation.navigate("Login")}
            >
              <Text style={styles.loginLink}>Masuk Disini</Text>
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
  inputLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#1E293B",
    marginBottom: 6,
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
      android: {
        elevation: 2,
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
  loginLink: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13,
    color: COLORS.brandBlue,
  },
});
