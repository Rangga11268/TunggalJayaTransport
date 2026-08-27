import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TextInput,
  TouchableOpacity,
  ScrollView,
  ActivityIndicator,
  Image,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { Colors, Radius } from '../theme/colors';
import { SafeAreaView } from 'react-native-safe-area-context';
import { useNavigation } from '@react-navigation/native';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';
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
} from 'lucide-react-native';
import { RootStackParamList } from '../navigation/RootNavigator';
import { COLORS } from '../theme/colors';
import { useAuth } from '../context/AuthContext';
import { User, Mail, Lock, Phone, ArrowLeft, ShieldCheck } from 'lucide-react-native';

export default function RegisterScreen({ navigation }: any) {
  const insets = useSafeAreaInsets();
type NavigationProp = NativeStackNavigationProp<RootStackParamList, 'Register'>;

export default function RegisterScreen() {
  const navigation = useNavigation<NavigationProp>();
  const { register } = useAuth();

  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [loading, setLoading] = useState(false);
  const [errorMsg, setErrorMsg] = useState('');
  const [focusedField, setFocusedField] = useState<string | null>(null);

  const handleRegister = async () => {
    if (!name.trim() || !email.trim() || !password) {
      setErrorMsg('Harap lengkapi semua data wajib.');
    if (!name.trim() || !email.trim() || !phone.trim() || !password) {
      setErrorMsg('Harap lengkapi seluruh kolom formulir.');
      return;
    }

    try {
      setLoading(true);
      setErrorMsg('');
      await register(name.trim(), email.trim(), password, phone.trim());
      navigation.replace('MainTabs');
      const ok = await register(name.trim(), email.trim(), password, phone.trim());
      if (ok) {
        navigation.replace('MainTabs');
      } else {
        setErrorMsg('Pendaftaran gagal. Periksa format data Anda.');
      }
    } catch (err: any) {
      setErrorMsg(err?.toString() || 'Registrasi gagal.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <KeyboardAvoidingView
      behavior={Platform.OS === 'ios' ? 'padding' : undefined}
      style={styles.container}
    >
      <ScrollView
        contentContainerStyle={[
          styles.scroll,
          { paddingTop: insets.top + 16, paddingBottom: insets.bottom + 24 },
        ]}
        showsVerticalScrollIndicator={false}
    <SafeAreaView edges={['top', 'bottom']} style={styles.container}>
      <KeyboardAvoidingView
        behavior={Platform.OS === 'ios' ? 'padding' : undefined}
        style={{ flex: 1 }}
      >
        {/* Top Header */}
        <View style={styles.topHeader}>
          <TouchableOpacity
            style={styles.backBtn}
            onPress={() => navigation.goBack()}
            activeOpacity={0.7}
          >
            <ArrowLeft size={18} color="#FFFFFF" />
          </TouchableOpacity>
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

          <View style={styles.brandBadge}>
            <Image
              source={require('../../assets/logo/logoNoBg.png')}
              style={styles.logoIcon}
              resizeMode="contain"
            />
            <Text style={styles.brandTitle}>TUNGGAL JAYA</Text>
            <View style={styles.sslBadge}>
              <ShieldCheck size={14} color="#059669" />
              <Text style={styles.sslText}>Enkripsi Aman SSL</Text>
            </View>
          </View>
        </View>

        <View style={styles.titleSection}>
          <Text style={styles.pageTitle}>Daftar Akun</Text>
          <Text style={styles.pageSubtitle}>Nikmati kemudahan pesan tiket bus & promo eksklusif</Text>
        </View>
          {/* Form Header */}
          <View style={styles.headerBox}>
            <Text style={styles.title}>Buat Akun Baru</Text>
            <Text style={styles.subtitle}>
              Daftar sekarang untuk kemudahan pemesanan tiket, riwayat perjalanan, dan reward loyalti VIP.
            </Text>
          </View>

        {/* Register Card */}
        <View style={styles.card}>
          <Text style={styles.cardTitle}>Data Diri Pengguna</Text>
          <Text style={styles.cardSubtitle}>Isi formulir berikut dengan benar</Text>

          {/* Error Message Box if any */}
          {errorMsg ? (
            <View style={styles.errorBox}>
              <AlertCircle size={18} color={COLORS.brandRed} />
              <Text style={styles.errorText}>{errorMsg}</Text>
            </View>
          ) : null}

          {/* Name Input */}
          <Text style={styles.inputLabel}>NAMA LENGKAP</Text>
          <View style={styles.inputContainer}>
            <User size={18} color={Colors.primary} style={styles.inputIcon} />
            <TextInput
              style={styles.input}
              placeholder="Contoh: Rangga Putra"
              placeholderTextColor={Colors.textMuted}
              value={name}
              onChangeText={setName}
            />
          </View>
          {/* Main Form Card */}
          <View style={styles.formCard}>
            {/* Full Name */}
            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Nama Lengkap (Sesuai KTP)</Text>
              <View
                style={[
                  styles.inputContainer,
                  focusedField === 'name' && styles.inputContainerFocused,
                ]}
              >
                <User size={18} color={focusedField === 'name' ? COLORS.brandRed : '#6B7280'} />
                <TextInput
                  style={styles.textInput}
                  placeholder="Contoh: Ahmad Pratama"
                  placeholderTextColor="#9CA3AF"
                  value={name}
                  onChangeText={setName}
                  onFocus={() => setFocusedField('name')}
                  onBlur={() => setFocusedField(null)}
                />
              </View>
            </View>

          {/* Email Input */}
          <Text style={styles.inputLabel}>EMAIL</Text>
          <View style={styles.inputContainer}>
            <Mail size={18} color={Colors.primary} style={styles.inputIcon} />
            <TextInput
              style={styles.input}
              placeholder="nama@email.com"
              placeholderTextColor={Colors.textMuted}
              keyboardType="email-address"
              autoCapitalize="none"
              value={email}
              onChangeText={setEmail}
            />
          </View>
            {/* Email Address */}
            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Alamat Email</Text>
              <View
                style={[
                  styles.inputContainer,
                  focusedField === 'email' && styles.inputContainerFocused,
                ]}
              >
                <Mail size={18} color={focusedField === 'email' ? COLORS.brandRed : '#6B7280'} />
                <TextInput
                  style={styles.textInput}
                  placeholder="nama@email.com"
                  placeholderTextColor="#9CA3AF"
                  keyboardType="email-address"
                  autoCapitalize="none"
                  value={email}
                  onChangeText={setEmail}
                  onFocus={() => setFocusedField('email')}
                  onBlur={() => setFocusedField(null)}
                />
              </View>
            </View>

          {/* Phone Input */}
          <Text style={styles.inputLabel}>NOMOR WHATSAPP</Text>
          <View style={styles.inputContainer}>
            <Phone size={18} color={Colors.primary} style={styles.inputIcon} />
            <TextInput
              style={styles.input}
              placeholder="08123456789"
              placeholderTextColor={Colors.textMuted}
              keyboardType="phone-pad"
              value={phone}
              onChangeText={setPhone}
            />
            {/* Phone Number */}
            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Nomor WhatsApp / HP</Text>
              <View
                style={[
                  styles.inputContainer,
                  focusedField === 'phone' && styles.inputContainerFocused,
                ]}
              >
                <Phone size={18} color={focusedField === 'phone' ? COLORS.brandRed : '#6B7280'} />
                <TextInput
                  style={styles.textInput}
                  placeholder="081234567890"
                  placeholderTextColor="#9CA3AF"
                  keyboardType="phone-pad"
                  value={phone}
                  onChangeText={setPhone}
                  onFocus={() => setFocusedField('phone')}
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
                  focusedField === 'password' && styles.inputContainerFocused,
                ]}
              >
                <Lock size={18} color={focusedField === 'password' ? COLORS.brandRed : '#6B7280'} />
                <TextInput
                  style={styles.textInput}
                  placeholder="Minimal 8 karakter"
                  placeholderTextColor="#9CA3AF"
                  secureTextEntry={!showPassword}
                  value={password}
                  onChangeText={setPassword}
                  onFocus={() => setFocusedField('password')}
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

          {/* Password Input */}
          <Text style={styles.inputLabel}>PASSWORD</Text>
          <View style={styles.inputContainer}>
            <Lock size={18} color={Colors.primary} style={styles.inputIcon} />
            <TextInput
              style={styles.input}
              placeholder="Minimal 8 karakter"
              placeholderTextColor={Colors.textMuted}
              secureTextEntry
              value={password}
              onChangeText={setPassword}
            />
          {/* Bottom Switch to Login */}
          <View style={styles.footerRow}>
            <Text style={styles.footerText}>Sudah memiliki akun?</Text>
            <TouchableOpacity
              activeOpacity={0.7}
              onPress={() => navigation.navigate('Login')}
            >
              <Text style={styles.loginLink}>Masuk Disini</Text>
            </TouchableOpacity>
          </View>

          {/* Submit Button */}
          <TouchableOpacity
            style={[styles.submitBtn, loading && styles.btnDisabled]}
            onPress={handleRegister}
            disabled={loading}
            activeOpacity={0.8}
          >
            {loading ? (
              <ActivityIndicator color="#FFFFFF" />
            ) : (
              <Text style={styles.submitBtnText}>Daftar Sekarang</Text>
            )}
          </TouchableOpacity>
        </View>

        {/* Footer Link */}
        <View style={styles.footerRow}>
          <Text style={styles.footerText}>Sudah memiliki akun?</Text>
          <TouchableOpacity onPress={() => navigation.navigate('Login')}>
            <Text style={styles.footerLink}> Masuk di Sini</Text>
          </TouchableOpacity>
        </View>

        {/* SSL Badge */}
        <View style={styles.trustBadge}>
          <ShieldCheck size={14} color={Colors.success} />
          <Text style={styles.trustText}> Data Anda terlindungi dengan standar keamanan TLS</Text>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
        </ScrollView>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: Colors.background,
    backgroundColor: COLORS.bgDark,
  },
  scroll: {
  scrollContent: {
    paddingHorizontal: 20,
    paddingTop: 10,
    paddingBottom: 40,
  },
  topHeader: {
  topBar: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
    marginBottom: 24,
  },
  backBtn: {
    width: 40,
    height: 40,
    backgroundColor: Colors.surfaceContainer,
    borderRadius: Radius.full,
    width: 42,
    height: 42,
    borderRadius: 21,
    backgroundColor: '#FFFFFF',
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: Colors.borderLight,
    borderColor: '#E2E8F0',
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.05,
        shadowRadius: 6,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  brandBadge: {
  sslBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: Colors.surfaceContainer,
    gap: 6,
    backgroundColor: 'rgba(5, 150, 105, 0.1)',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: Radius.full,
    borderWidth: 1,
    borderColor: Colors.borderLight,
    borderRadius: 20,
  },
  logoIcon: {
    width: 18,
    height: 18,
    marginRight: 6,
  },
  brandTitle: {
    color: '#FFFFFF',
    fontWeight: '900',
  sslText: {
    fontFamily: 'PlusJakartaSans_600SemiBold',
    fontSize: 11,
    letterSpacing: 0.5,
    color: '#059669',
  },
  titleSection: {
    marginBottom: 18,
  headerBox: {
    marginBottom: 20,
  },
  pageTitle: {
    fontSize: 26,
    fontWeight: '900',
    color: '#FFFFFF',
  title: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 28,
    color: '#111827',
    marginBottom: 6,
    letterSpacing: -0.5,
  },
  pageSubtitle: {
  subtitle: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 14,
    color: '#4B5563',
    lineHeight: 22,
  },
  errorBox: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 10,
    backgroundColor: 'rgba(230, 0, 35, 0.08)',
    borderRadius: 14,
    padding: 14,
    borderWidth: 1,
    borderColor: 'rgba(230, 0, 35, 0.25)',
    marginBottom: 18,
  },
  errorText: {
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 13,
    color: Colors.textSecondary,
    marginTop: 4,
    color: COLORS.brandRed,
    flex: 1,
  },
  card: {
    backgroundColor: Colors.surfaceCard,
  formCard: {
    backgroundColor: '#FFFFFF',
    borderRadius: 24,
    borderWidth: 1.2,
    borderColor: Colors.border,
    padding: 22,
    shadowColor: '#000000',
    shadowOffset: { width: 0, height: 8 },
    shadowOpacity: 0.5,
    shadowRadius: 24,
    elevation: 8,
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: '800',
    color: '#FFFFFF',
  },
  cardSubtitle: {
    fontSize: 12,
    color: Colors.textSecondary,
    marginTop: 2,
    marginBottom: 16,
  },
  errorBox: {
    backgroundColor: Colors.errorContainer,
    padding: 12,
    borderRadius: Radius.md,
    marginBottom: 14,
    borderWidth: 1,
    borderColor: 'rgba(239, 68, 68, 0.3)',
    borderColor: '#E2E8F0',
    marginBottom: 24,
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 8 },
        shadowOpacity: 0.06,
        shadowRadius: 16,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  errorText: {
    color: '#FCA5A5',
    fontSize: 12,
    fontWeight: '600',
  inputGroup: {
    marginBottom: 18,
  },
  inputLabel: {
    color: '#FFFFFF',
    fontSize: 10,
    fontWeight: '800',
    letterSpacing: 0.5,
    marginBottom: 6,
    marginTop: 6,
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 13,
    color: '#111827',
    marginBottom: 8,
  },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: Colors.surfaceContainer,
    borderRadius: Radius.pill,
    borderWidth: 1,
    borderColor: Colors.borderLight,
    backgroundColor: '#F1F4F8',
    borderRadius: 16,
    borderWidth: 1.5,
    borderColor: '#E2E8F0',
    paddingHorizontal: 16,
    height: 50,
    marginBottom: 10,
    height: 52,
    gap: 12,
  },
  inputIcon: {
    marginRight: 10,
  inputContainerFocused: {
    borderColor: COLORS.brandRed,
    backgroundColor: '#FFFFFF',
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
  input: {
  textInput: {
    flex: 1,
    color: '#FFFFFF',
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 14,
    fontWeight: '600',
    color: '#111827',
  },
  eyeBtn: {
    padding: 6,
  },
  submitBtn: {
    height: 52,
    backgroundColor: Colors.primary,
    borderRadius: Radius.pill,
    borderRadius: 26,
    backgroundColor: COLORS.brandRed,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 14,
    shadowColor: Colors.primary,
    shadowOffset: { width: 0, height: 6 },
    shadowOpacity: 0.4,
    shadowRadius: 16,
    elevation: 6,
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
  btnDisabled: {
    opacity: 0.6,
  submitBtnDisabled: {
    opacity: 0.7,
  },
  submitBtnText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 15,
    color: '#FFFFFF',
    fontSize: 15,
    fontWeight: '800',
  },
  footerRow: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 24,
    gap: 6,
  },
  footerText: {
    color: Colors.textSecondary,
    fontSize: 13,
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 14,
    color: '#4B5563',
  },
  footerLink: {
    color: Colors.primary,
    fontSize: 13,
    fontWeight: '800',
  loginLink: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 14,
    color: COLORS.brandRed,
  },
  trustBadge: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 18,
  },
  trustText: {
    color: Colors.textMuted,
    fontSize: 11,
  },
});
