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
  AlertCircle,
} from "lucide-react-native";
import { RootStackParamList } from "../navigation/RootNavigator";
import { COLORS } from "../theme/colors";
import { useAuth } from "../context/AuthContext";

type NavigationProp = NativeStackNavigationProp<RootStackParamList, "Register">;

export default function RegisterScreen() {
  const navigation = useNavigation<NavigationProp>();
  const { register } = useAuth();

  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [password, setPassword] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [loading, setLoading] = useState(false);
  const [errorMsg, setErrorMsg] = useState("");
  const [focusedField, setFocusedField] = useState<string | null>(null);

  const handleRegister = async () => {
    if (!name.trim() || !email.trim() || !phone.trim() || !password) {
      setErrorMsg("Harap lengkapi seluruh kolom formulir.");
      return;
    }

    try {
      setLoading(true);
      setErrorMsg("");
      const ok = await register(
        name.trim(),
        email.trim(),
        password,
        phone.trim(),
      );
      if (ok) {
        navigation.replace("MainTabs");
      } else {
        setErrorMsg("Pendaftaran gagal. Periksa format data Anda.");
      }
    } catch (err: any) {
      setErrorMsg(err?.toString() || "Registrasi gagal.");
    } finally {
      setLoading(false);
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
              onPress={() => navigation.goBack()}
              style={styles.backBtn}
            >
              <ArrowLeft size={18} color="#111827" />
            </TouchableOpacity>

            <View style={styles.sslBadge}>
              <ShieldCheck size={14} color="#059669" />
              <Text style={styles.sslText}>Enkripsi Aman SSL</Text>
            </View>
          </View>

          {/* Form Header */}
          <View style={styles.headerBox}>
            <Text style={styles.title}>Buat Akun Baru</Text>
            <Text style={styles.subtitle}>
              Daftar sekarang untuk kemudahan pemesanan tiket, riwayat
              perjalanan, dan reward poin member.
            </Text>
          </View>

          {/* Error Message Box if any */}
          {errorMsg ? (
            <View style={styles.errorBox}>
              <AlertCircle size={18} color={COLORS.brandRed} />
              <Text style={styles.errorText}>{errorMsg}</Text>
            </View>
          ) : null}

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
                  color={focusedField === "name" ? COLORS.brandRed : "#6B7280"}
                />
                <TextInput
                  style={styles.textInput}
                  placeholder="Contoh: Ahmad Pratama"
                  placeholderTextColor="#9CA3AF"
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
                  color={focusedField === "email" ? COLORS.brandRed : "#6B7280"}
                />
                <TextInput
                  style={styles.textInput}
                  placeholder="nama@email.com"
                  placeholderTextColor="#9CA3AF"
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
                  color={focusedField === "phone" ? COLORS.brandRed : "#6B7280"}
                />
                <TextInput
                  style={styles.textInput}
                  placeholder="081234567890"
                  placeholderTextColor="#9CA3AF"
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
              <Text style={styles.inputLabel}>Kata Sandi</Text>
              <View
                style={[
                  styles.inputContainer,
                  focusedField === "password" && styles.inputContainerFocused,
                ]}
              >
                <Lock
                  size={18}
                  color={
                    focusedField === "password" ? COLORS.brandRed : "#6B7280"
                  }
                />
                <TextInput
                  style={styles.textInput}
                  placeholder="Minimal 8 karakter"
                  placeholderTextColor="#9CA3AF"
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
                    <EyeOff size={18} color="#6B7280" />
                  ) : (
                    <Eye size={18} color="#6B7280" />
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
                <Text style={styles.submitBtnText}>Daftar Sekarang</Text>
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
  },
  topBar: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 24,
  },
  backBtn: {
    width: 42,
    height: 42,
    borderRadius: 21,
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
    backgroundColor: "rgba(5, 150, 105, 0.1)",
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 20,
  },
  sslText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11,
    color: "#059669",
  },
  headerBox: {
    marginBottom: 20,
  },
  title: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 28,
    color: "#111827",
    marginBottom: 6,
    letterSpacing: -0.5,
  },
  subtitle: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 14,
    color: "#4B5563",
    lineHeight: 22,
  },
  errorBox: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
    backgroundColor: "rgba(230, 0, 35, 0.08)",
    borderRadius: 14,
    padding: 14,
    borderWidth: 1,
    borderColor: "rgba(230, 0, 35, 0.25)",
    marginBottom: 18,
  },
  errorText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 13,
    color: COLORS.brandRed,
    flex: 1,
  },
  formCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 24,
    padding: 22,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 24,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 8 },
        shadowOpacity: 0.06,
        shadowRadius: 16,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  inputGroup: {
    marginBottom: 18,
  },
  inputLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#111827",
    marginBottom: 8,
  },
  inputContainer: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F1F4F8",
    borderRadius: 16,
    borderWidth: 1.5,
    borderColor: "#E2E8F0",
    paddingHorizontal: 16,
    height: 52,
    gap: 12,
  },
  inputContainerFocused: {
    borderColor: COLORS.brandRed,
    backgroundColor: "#FFFFFF",
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandRed,
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.15,
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
    fontSize: 14,
    color: "#111827",
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
    height: 52,
    borderRadius: 26,
    backgroundColor: COLORS.brandRed,
    justifyContent: "center",
    alignItems: "center",
    marginTop: 8,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandRed,
        shadowOffset: { width: 0, height: 6 },
        shadowOpacity: 0.35,
        shadowRadius: 10,
      },
      android: {
        elevation: 6,
      },
    }),
  },
  submitBtnDisabled: {
    opacity: 0.7,
  },
  submitBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 15,
    color: "#FFFFFF",
  },
  footerRow: {
    flexDirection: "row",
    justifyContent: "center",
    alignItems: "center",
    gap: 6,
  },
  footerText: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 14,
    color: "#4B5563",
  },
  loginLink: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 14,
    color: COLORS.brandRed,
  },
});
