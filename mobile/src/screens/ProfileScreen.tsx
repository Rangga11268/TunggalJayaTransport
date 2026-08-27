import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Image,
  Alert,
  Platform,
} from 'react-native';
import { SafeAreaView, useSafeAreaInsets } from 'react-native-safe-area-context';
import { Colors, Radius, COLORS } from '../theme/colors';
import { useAuth } from '../context/AuthContext';
import {
  User,
  Crown,
  Sparkles,
  Gift,
  Shield,
  HelpCircle,
  LogOut,
  ChevronRight,
  Phone,
  Mail,
  Ticket,
} from 'lucide-react-native';

export default function ProfileScreen({ navigation }: any) {
  const insets = useSafeAreaInsets();
  const { user, logout } = useAuth();

  const handleLogout = () => {
    Alert.alert('Konfirmasi Keluar', 'Apakah Anda yakin ingin keluar dari akun?', [
      { text: 'Batal', style: 'cancel' },
      {
        text: 'Keluar',
        style: 'destructive',
        onPress: async () => {
          await logout();
          navigation.replace('GetStarted');
        },
      },
    ]);
  };

  return (
    <View style={styles.container}>
      {/* Top Header */}
      <SafeAreaView edges={['top']} style={styles.topHeader}>
        <Text style={styles.headerTitle}>Profil Saya</Text>
      </SafeAreaView>

      <ScrollView
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}
      >
        {/* VIP Loyalty Card (Gold Light / Alabaster) */}
        <View style={styles.vipCard}>
          <View style={styles.vipHeader}>
            <View style={styles.vipBadge}>
              <Crown size={14} color="#D97706" style={{ marginRight: 4 }} />
              <Text style={styles.vipBadgeText}>VIP MEMBER</Text>
            </View>
            <Image
              source={require('../../assets/images/logoNoBg.png')}
              style={styles.vipLogo}
              resizeMode="contain"
            />
          </View>

          <Text style={styles.userName}>{user?.name || 'Rangga Pratama'}</Text>
          <Text style={styles.userEmail}>{user?.email || 'penumpang@example.com'}</Text>

          <View style={styles.pointsRow}>
            <View>
              <Text style={styles.pointsLabel}>TJ Poin Rewards</Text>
              <Text style={styles.pointsVal}>1.450 Poin</Text>
            </View>
            <TouchableOpacity
              style={styles.redeemBtn}
              onPress={() => navigation.navigate('Promo')}
              activeOpacity={0.8}
            >
              <Gift size={12} color="#FFFFFF" style={{ marginRight: 4 }} />
              <Text style={styles.redeemText}>Tukar Kupon</Text>
            </TouchableOpacity>
          </View>
        </View>

        {/* Menu Items Card */}
        <View style={styles.menuCard}>
          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate('Promo')}
            activeOpacity={0.8}
          >
            <View style={styles.menuIconCircle}>
              <Gift size={18} color={COLORS.brandRed} />
            </View>
            <Text style={styles.menuTitle}>Voucher &amp; Kupon Saya</Text>
            <ChevronRight size={16} color="#9CA3AF" />
          </TouchableOpacity>

          <View style={styles.divider} />

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate('Charter')}
            activeOpacity={0.8}
          >
            <View style={styles.menuIconCircle}>
              <Sparkles size={18} color={COLORS.brandRed} />
            </View>
            <Text style={styles.menuTitle}>Sewa Bus Pariwisata</Text>
            <ChevronRight size={16} color="#9CA3AF" />
          </TouchableOpacity>

          <View style={styles.divider} />

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate('Help')}
            activeOpacity={0.8}
          >
            <View style={styles.menuIconCircle}>
              <HelpCircle size={18} color={COLORS.brandRed} />
            </View>
            <Text style={styles.menuTitle}>Pusat Bantuan &amp; FAQ</Text>
            <ChevronRight size={16} color="#9CA3AF" />
          </TouchableOpacity>
        </View>

        {/* Logout Button */}
        <TouchableOpacity
          style={styles.logoutBtn}
          onPress={handleLogout}
          activeOpacity={0.8}
        >
          <LogOut size={18} color={COLORS.brandRed} style={{ marginRight: 8 }} />
          <Text style={styles.logoutText}>Keluar dari Akun</Text>
        </TouchableOpacity>

        <Text style={styles.versionText}>Tunggal Jaya Transport v2.4.0 • Build 2026</Text>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  topHeader: {
    backgroundColor: '#FFFFFF',
    paddingHorizontal: 20,
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#E2E8F0',
  },
  headerTitle: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 20,
    color: '#111827',
    letterSpacing: -0.3,
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingTop: 16,
    paddingBottom: 120,
  },
  vipCard: {
    backgroundColor: '#FFFFFF',
    borderRadius: 22,
    padding: 20,
    borderWidth: 1,
    borderColor: '#E2E8F0',
    marginBottom: 20,
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 6 },
        shadowOpacity: 0.06,
        shadowRadius: 14,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  vipHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 16,
  },
  vipBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'rgba(217, 119, 6, 0.12)',
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 12,
  },
  vipBadgeText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 11,
    color: '#D97706',
  },
  vipLogo: {
    width: 32,
    height: 32,
  },
  userName: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 20,
    color: '#111827',
    marginBottom: 2,
  },
  userEmail: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 13,
    color: '#6B7280',
    marginBottom: 18,
  },
  pointsRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#F1F4F8',
    borderRadius: 14,
    paddingHorizontal: 16,
    paddingVertical: 12,
  },
  pointsLabel: {
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 11,
    color: '#6B7280',
  },
  pointsVal: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 16,
    color: '#111827',
  },
  redeemBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: COLORS.brandRed,
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 12,
  },
  redeemText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 11,
    color: '#FFFFFF',
  },
  menuCard: {
    backgroundColor: '#FFFFFF',
    borderRadius: 20,
    borderWidth: 1,
    borderColor: '#E2E8F0',
    overflow: 'hidden',
    marginBottom: 20,
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.04,
        shadowRadius: 10,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  menuItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 18,
    paddingVertical: 16,
  },
  menuIconCircle: {
    width: 38,
    height: 38,
    borderRadius: 19,
    backgroundColor: 'rgba(230, 0, 35, 0.08)',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 14,
  },
  menuTitle: {
    flex: 1,
    fontFamily: 'PlusJakartaSans_600SemiBold',
    fontSize: 14,
    color: '#111827',
  },
  divider: {
    height: 1,
    backgroundColor: '#F1F4F8',
    marginLeft: 70,
  },
  logoutBtn: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#FFFFFF',
    borderRadius: 16,
    borderWidth: 1,
    borderColor: '#E2E8F0',
    height: 50,
    marginBottom: 20,
  },
  logoutText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 14,
    color: COLORS.brandRed,
  },
  versionText: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 11,
    color: '#9CA3AF',
    textAlign: 'center',
  },
});
