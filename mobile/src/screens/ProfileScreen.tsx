import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Image,
  Alert,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { Colors, Radius } from '../theme/colors';
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
      <View style={[styles.topHeader, { paddingTop: insets.top + 10 }]}>
        <Text style={styles.headerTitle}>Profil Saya</Text>
      </View>

      <ScrollView
        contentContainerStyle={{ paddingHorizontal: 20, paddingBottom: 120 }}
        showsVerticalScrollIndicator={false}
      >
        {/* VIP Loyalty Card (Gold Obsidian) */}
        <View style={styles.vipCard}>
          <View style={styles.vipHeader}>
            <View style={styles.vipBadge}>
              <Crown size={14} color="#F59E0B" style={{ marginRight: 4 }} />
              <Text style={styles.vipBadgeText}>VIP MEMBER</Text>
            </View>
            <Image
              source={require('../../assets/logo/logoNoBg.png')}
              style={styles.vipLogo}
              resizeMode="contain"
            />
          </View>

          <Text style={styles.userName}>{user?.name || 'Rangga Putra'}</Text>
          <Text style={styles.userEmail}>{user?.email || 'user@example.com'}</Text>

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
              <Gift size={12} color="#000000" style={{ marginRight: 4 }} />
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
              <Gift size={16} color={Colors.primary} />
            </View>
            <Text style={styles.menuTitle}>Voucher & Kupon Saya</Text>
            <ChevronRight size={16} color={Colors.textSecondary} />
          </TouchableOpacity>

          <View style={styles.divider} />

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate('Charter')}
            activeOpacity={0.8}
          >
            <View style={styles.menuIconCircle}>
              <Sparkles size={16} color={Colors.primary} />
            </View>
            <Text style={styles.menuTitle}>Sewa Pariwisata</Text>
            <ChevronRight size={16} color={Colors.textSecondary} />
          </TouchableOpacity>

          <View style={styles.divider} />

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate('Help')}
            activeOpacity={0.8}
          >
            <View style={styles.menuIconCircle}>
              <HelpCircle size={16} color={Colors.primary} />
            </View>
            <Text style={styles.menuTitle}>Pusat Bantuan & CS 24 Jam</Text>
            <ChevronRight size={16} color={Colors.textSecondary} />
          </TouchableOpacity>

          <View style={styles.divider} />

          <TouchableOpacity
            style={styles.menuItem}
            onPress={handleLogout}
            activeOpacity={0.8}
          >
            <View style={[styles.menuIconCircle, { backgroundColor: Colors.errorContainer }]}>
              <LogOut size={16} color={Colors.error} />
            </View>
            <Text style={[styles.menuTitle, { color: Colors.error }]}>
              Keluar Akun
            </Text>
            <ChevronRight size={16} color={Colors.error} />
          </TouchableOpacity>
        </View>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: Colors.background,
  },
  topHeader: {
    paddingHorizontal: 20,
    paddingBottom: 14,
    backgroundColor: Colors.surfaceCard,
    borderBottomWidth: 1.2,
    borderBottomColor: Colors.border,
  },
  headerTitle: {
    fontSize: 20,
    fontWeight: '900',
    color: '#FFFFFF',
  },
  vipCard: {
    backgroundColor: '#1E1A18',
    borderRadius: 26,
    borderWidth: 1.5,
    borderColor: '#4A3B18',
    padding: 22,
    marginTop: 16,
  },
  vipHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 14,
  },
  vipBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#382A0E',
    paddingHorizontal: 10,
    paddingVertical: 5,
    borderRadius: Radius.pill,
  },
  vipBadgeText: {
    color: '#F59E0B',
    fontSize: 10,
    fontWeight: '900',
  },
  vipLogo: {
    width: 24,
    height: 24,
  },
  userName: {
    color: '#FFFFFF',
    fontSize: 20,
    fontWeight: '900',
  },
  userEmail: {
    color: Colors.textSecondary,
    fontSize: 12,
    marginTop: 2,
  },
  pointsRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginTop: 18,
    paddingTop: 14,
    borderTopWidth: 1,
    borderTopColor: '#3D3014',
  },
  pointsLabel: {
    color: '#D4A373',
    fontSize: 11,
  },
  pointsVal: {
    color: '#FFFFFF',
    fontSize: 16,
    fontWeight: '900',
  },
  redeemBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#F59E0B',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: Radius.pill,
  },
  redeemText: {
    color: '#000000',
    fontSize: 11,
    fontWeight: '800',
  },
  menuCard: {
    backgroundColor: Colors.surfaceCard,
    borderRadius: 24,
    borderWidth: 1.2,
    borderColor: Colors.border,
    padding: 14,
    marginTop: 16,
  },
  menuItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 10,
    paddingHorizontal: 8,
  },
  menuIconCircle: {
    width: 38,
    height: 38,
    borderRadius: Radius.full,
    backgroundColor: Colors.surfaceContainer,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 14,
  },
  menuTitle: {
    flex: 1,
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '700',
  },
  divider: {
    height: 1,
    backgroundColor: Colors.border,
    marginVertical: 4,
  },
});
