import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Image,
  Dimensions,
  Platform,
  RefreshControl,
  Modal,
  Alert,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { useNavigation } from '@react-navigation/native';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';
import {
  Menu,
  Bell,
  MapPin,
  Bus,
  Compass,
  Ticket,
  ChevronRight,
  CreditCard,
  Wifi,
  Tv,
  Coffee,
  Receipt,
  HelpCircle,
  User,
  LogOut,
  X,
  Crown,
  Tag,
  ShieldCheck,
} from 'lucide-react-native';
import { RootStackParamList } from '../navigation/RootNavigator';
import { COLORS } from '../theme/colors';
import { useAuth } from '../context/AuthContext';
import apiClient from '../api/client';

const { width } = Dimensions.get('window');

type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

export default function HomeScreen() {
  const navigation = useNavigation<NavigationProp>();
  const { user, logout } = useAuth();

  const [schedules, setSchedules] = useState<any[]>([]);
  const [refreshing, setRefreshing] = useState(false);
  const [isDrawerOpen, setIsDrawerOpen] = useState(false);
  const [originCity] = useState('Jakarta');
  const [destinationCity] = useState('Kuningan');

  // Prominent Quick Actions Grid (Spacious 2x2 with clear functional icons)
  const quickLinks = [
    {
      id: 'schedules',
      title: 'Tiket Bus AKAP',
      subtitle: 'Jadwal & Kursi',
      icon: Bus,
      iconColor: '#E60023',
      bgColor: 'rgba(230, 0, 35, 0.08)',
      action: () => navigation.navigate('Schedules'),
    },
    {
      id: 'charter',
      title: 'Sewa Pariwisata',
      subtitle: 'Carter Rombongan',
      icon: Compass,
      iconColor: '#2563EB',
      bgColor: 'rgba(37, 99, 235, 0.08)',
      action: () => navigation.navigate('Charter'),
    },
    {
      id: 'history',
      title: 'Riwayat Pesanan',
      subtitle: 'Cek E-Tiket',
      icon: Receipt,
      iconColor: '#059669',
      bgColor: 'rgba(5, 150, 105, 0.08)',
      action: () => navigation.navigate('MainTabs', { screen: 'BookingHistory' } as any),
    },
    {
      id: 'promo',
      title: 'Voucher Promo',
      subtitle: 'Diskon s.d 50%',
      icon: Tag,
      iconColor: '#D97706',
      bgColor: 'rgba(217, 119, 6, 0.08)',
      action: () => navigation.navigate('Promo'),
    },
  ];

  const fetchHomeData = async () => {
    try {
      const response = await apiClient.get('/schedules').catch(() => ({ data: [] }));
      const list = Array.isArray(response.data)
        ? response.data
        : response.data?.data || [];
      setSchedules(list.slice(0, 4));
    } catch (e) {
      console.log('Error loading home data:', e);
    }
  };

  useEffect(() => {
    fetchHomeData();
  }, []);

  const onRefresh = async () => {
    setRefreshing(true);
    await fetchHomeData();
    setRefreshing(false);
  };

  const handleLogout = () => {
    setIsDrawerOpen(false);
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
      <SafeAreaView edges={['top']} style={styles.safeHeader}>
        {/* Top App Bar */}
        <View style={styles.headerBar}>
          <TouchableOpacity
            activeOpacity={0.7}
            onPress={() => setIsDrawerOpen(true)}
            style={styles.iconCircle}
          >
            <Menu size={20} color="#111827" />
          </TouchableOpacity>

          <View style={styles.headerRight}>
            <TouchableOpacity
              activeOpacity={0.7}
              onPress={() => Alert.alert('Notifikasi', 'Tidak ada notifikasi baru.')}
              style={styles.iconCircle}
            >
              <Bell size={18} color="#111827" />
              <View style={styles.badgeDot} />
            </TouchableOpacity>

            <TouchableOpacity
              activeOpacity={0.8}
              onPress={() => navigation.navigate('MainTabs', { screen: 'Profile' } as any)}
              style={styles.avatarRing}
            >
              <Image
                source={require('../../assets/images/bentas01.webp')}
                style={styles.avatarImg}
              />
            </TouchableOpacity>
          </View>
        </View>
      </SafeAreaView>

      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContent}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={COLORS.brandRed} />
        }
      >
        {/* Display Headline */}
        <View style={styles.headlineWrapper}>
          <Text style={styles.headline}>
            Explore{'\n'}
            <Text style={styles.headlineSub}>Go Every Where?</Text>
          </Text>
        </View>

        {/* Location Search Capsule */}
        <TouchableOpacity
          activeOpacity={0.85}
          onPress={() => navigation.navigate('Schedules', { origin: originCity, destination: destinationCity })}
          style={styles.locationCapsule}
        >
          <Text style={styles.locationText}>
            Current: <Text style={styles.locationBold}>{originCity} → {destinationCity}</Text>
          </Text>
          <View style={styles.locationRedBtn}>
            <MapPin size={16} color="#FFFFFF" />
          </View>
        </TouchableOpacity>

        {/* PROMINENT QUICK ACTIONS GRID (2x2 Layout) */}
        <View style={styles.quickSection}>
          <View style={styles.sectionHeaderNoMargin}>
            <Text style={styles.sectionTitle}>Layanan Utama</Text>
            <Text style={styles.sectionSub}>Akses Cepat</Text>
          </View>

          <View style={styles.quickGrid}>
            {quickLinks.map((item) => {
              const IconComp = item.icon;
              return (
                <TouchableOpacity
                  key={item.id}
                  activeOpacity={0.85}
                  onPress={item.action}
                  style={styles.quickCard}
                >
                  <View style={[styles.quickIconCircle, { backgroundColor: item.bgColor }]}>
                    <IconComp size={22} color={item.iconColor} />
                  </View>
                  <Text style={styles.quickTitle} numberOfLines={1}>
                    {item.title}
                  </Text>
                  <Text style={styles.quickSubtitle} numberOfLines={1}>
                    {item.subtitle}
                  </Text>
                </TouchableOpacity>
              );
            })}
          </View>
        </View>

        {/* Featured Hero Card (Tunggal Jaya Jetbus 5 Super High Deck Adiputro - NO DOUBLE DECKER) */}
        <View style={styles.heroCardContainer}>
          <LinearGradient
            colors={['#FFFFFF', '#F8FAFC']}
            style={styles.heroCard}
          >
            <View style={styles.heroLeft}>
              <View style={styles.heroBadges}>
                <View style={styles.heroBadge}>
                  <Text style={styles.heroBadgeText}>Adiputro SHD</Text>
                </View>
                <View style={styles.heroBadge}>
                  <Text style={styles.heroBadgeText}>Air Suspension</Text>
                </View>
              </View>

              <Text style={styles.heroTitle}>
                Jetbus 5{'\n'}Super High Deck
              </Text>

              <TouchableOpacity
                activeOpacity={0.85}
                onPress={() => navigation.navigate('Schedules')}
                style={styles.slideBar}
              >
                <View style={styles.slideIconBox}>
                  <CreditCard size={15} color="#FFFFFF" />
                </View>
                <Text style={styles.slideArrowText}>&gt;&gt;&gt;</Text>
                <View style={styles.slideEndIcon}>
                  <Ticket size={15} color={COLORS.brandRed} />
                </View>
              </TouchableOpacity>
            </View>

            <View style={styles.heroRight}>
              <Image
                source={require('../../assets/images/resiBisma.webp')}
                style={styles.heroBusImage}
                resizeMode="contain"
              />
            </View>
          </LinearGradient>
        </View>

        {/* What's New Stories Section */}
        <View style={styles.sectionHeader}>
          <Text style={styles.sectionTitle}>What's new</Text>
          <TouchableOpacity
            activeOpacity={0.7}
            onPress={() => navigation.navigate('Promo')}
            style={styles.viewAllBtn}
          >
            <Text style={styles.viewAllText}>View &gt;</Text>
          </TouchableOpacity>
        </View>

        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={styles.whatsNewScroll}
        >
          <TouchableOpacity
            activeOpacity={0.85}
            onPress={() => navigation.navigate('Schedules')}
            style={styles.storyCard}
          >
            <Image
              source={require('../../assets/images/bentas01.webp')}
              style={styles.storyImage}
              resizeMode="cover"
            />
            <LinearGradient
              colors={['transparent', 'rgba(17, 24, 39, 0.88)']}
              style={styles.storyGradient}
            >
              <Text style={styles.storyTitle}>Bentas-01 Kuningan - Jkt</Text>
              <Text style={styles.storySubtitle}>Jadwal harian rute favorit via Tol Cipali</Text>
              <View style={styles.storyPill}>
                <Text style={styles.storyPillText}>Cek Jadwal &gt;</Text>
              </View>
            </LinearGradient>
          </TouchableOpacity>

          <TouchableOpacity
            activeOpacity={0.85}
            onPress={() => navigation.navigate('Charter')}
            style={styles.storyCard}
          >
            <Image
              source={require('../../assets/images/kylorenParwis.webp')}
              style={styles.storyImage}
              resizeMode="cover"
            />
            <LinearGradient
              colors={['transparent', 'rgba(17, 24, 39, 0.88)']}
              style={styles.storyGradient}
            >
              <Text style={styles.storyTitle}>Kylo Ren Jetbus 5 SHD</Text>
              <Text style={styles.storySubtitle}>Armada pariwisata VIP Hino RM 280</Text>
              <View style={styles.storyPill}>
                <Text style={styles.storyPillText}>Sewa Unit &gt;</Text>
              </View>
            </LinearGradient>
          </TouchableOpacity>
        </ScrollView>

        {/* Popular Routes List */}
        <View style={styles.sectionHeader}>
          <Text style={styles.sectionTitle}>Rute Populer AKAP</Text>
          <TouchableOpacity
            activeOpacity={0.7}
            onPress={() => navigation.navigate('Schedules')}
            style={styles.viewAllBtn}
          >
            <Text style={styles.viewAllText}>Semua &gt;</Text>
          </TouchableOpacity>
        </View>

        <View style={styles.scheduleList}>
          {schedules.map((item, idx) => {
            const busName = item.bus?.name || (idx % 2 === 0 ? 'Resi Bisma' : 'Primadona');
            const origin = item.route?.origin || 'Kuningan';
            const destination = item.route?.destination || (idx % 2 === 0 ? 'Jakarta (Kalideres)' : 'Jakarta (Roxy)');
            const price = Number(item.price || 140000).toLocaleString('id-ID');
            const depTime = item.departure_time ? item.departure_time.substring(0, 5) : '07:45';

            return (
              <TouchableOpacity
                key={item.id || idx}
                activeOpacity={0.85}
                onPress={() => navigation.navigate('ScheduleDetail', { scheduleId: item.id || 1 })}
                style={styles.scheduleCard}
              >
                <Image
                  source={idx % 2 === 0 ? require('../../assets/images/resiBisma.webp') : require('../../assets/images/primadona.webp')}
                  style={styles.scheduleBusThumb}
                />
                <View style={styles.scheduleMiddle}>
                  <Text style={styles.scheduleRoute}>{origin} → {destination}</Text>
                  <Text style={styles.scheduleBusName}>{busName} • {depTime} WIB</Text>
                  <Text style={styles.schedulePrice}>
                    Rp {price} <Text style={styles.schedulePerPerson}>/ org</Text>
                  </Text>
                </View>
                <View style={styles.scheduleAction}>
                  <View style={styles.ratingBadge}>
                    <Text style={styles.ratingText}>★ 4.9</Text>
                  </View>
                  <View style={styles.chevronCircle}>
                    <ChevronRight size={16} color="#FFFFFF" />
                  </View>
                </View>
              </TouchableOpacity>
            );
          })}
        </View>

        {/* Fleet Amenities Feature Bar */}
        <View style={styles.amenitiesCard}>
          <Text style={styles.amenitiesTitle}>Fasilitas Standar Eksekutif Tunggal Jaya</Text>
          <View style={styles.amenitiesGrid}>
            <View style={styles.amenityItem}>
              <View style={styles.amenityIconCircle}>
                <Wifi size={16} color={COLORS.brandRed} />
              </View>
              <Text style={styles.amenityText}>Free Wi-Fi</Text>
            </View>
            <View style={styles.amenityItem}>
              <View style={styles.amenityIconCircle}>
                <Tv size={16} color={COLORS.brandRed} />
              </View>
              <Text style={styles.amenityText}>Smart TV &amp; Audio</Text>
            </View>
            <View style={styles.amenityItem}>
              <View style={styles.amenityIconCircle}>
                <Coffee size={16} color={COLORS.brandRed} />
              </View>
              <Text style={styles.amenityText}>Full AC &amp; Reclining</Text>
            </View>
            <View style={styles.amenityItem}>
              <View style={styles.amenityIconCircle}>
                <ShieldCheck size={16} color={COLORS.brandRed} />
              </View>
              <Text style={styles.amenityText}>Kru Resmi TJ</Text>
            </View>
          </View>
        </View>

        <View style={{ height: 110 }} />
      </ScrollView>

      {/* Side Drawer Modal (Hamburger Menu) */}
      <Modal
        visible={isDrawerOpen}
        animationType="fade"
        transparent={true}
        onRequestClose={() => setIsDrawerOpen(false)}
      >
        <View style={styles.modalOverlay}>
          <TouchableOpacity
            activeOpacity={1}
            onPress={() => setIsDrawerOpen(false)}
            style={styles.modalBackdrop}
          />

          <View style={styles.drawerContainer}>
            <SafeAreaView edges={['top', 'bottom']} style={styles.drawerContent}>
              <View style={styles.drawerHeader}>
                <View style={styles.drawerUserRow}>
                  <View style={styles.drawerAvatar}>
                    <User size={24} color="#111827" />
                  </View>
                  <View style={styles.drawerUserInfo}>
                    <Text style={styles.drawerUserName}>{user?.name || 'Tamu Pengguna'}</Text>
                    <Text style={styles.drawerUserEmail}>{user?.email || 'Belum masuk akun'}</Text>
                    <View style={styles.vipBadge}>
                      <Crown size={12} color="#D97706" style={{ marginRight: 4 }} />
                      <Text style={styles.vipBadgeText}>VIP Member</Text>
                    </View>
                  </View>
                </View>

                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => setIsDrawerOpen(false)}
                  style={styles.drawerCloseBtn}
                >
                  <X size={20} color="#111827" />
                </TouchableOpacity>
              </View>

              <ScrollView style={styles.drawerLinks} showsVerticalScrollIndicator={false}>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => {
                    setIsDrawerOpen(false);
                    navigation.navigate('Schedules');
                  }}
                  style={styles.drawerLinkItem}
                >
                  <Bus size={18} color={COLORS.brandRed} style={{ marginRight: 14 }} />
                  <Text style={styles.drawerLinkText}>Pesan Tiket Bus AKAP</Text>
                  <ChevronRight size={16} color="#9CA3AF" />
                </TouchableOpacity>

                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => {
                    setIsDrawerOpen(false);
                    navigation.navigate('Charter');
                  }}
                  style={styles.drawerLinkItem}
                >
                  <Compass size={18} color="#2563EB" style={{ marginRight: 14 }} />
                  <Text style={styles.drawerLinkText}>Sewa Bus Pariwisata</Text>
                  <ChevronRight size={16} color="#9CA3AF" />
                </TouchableOpacity>

                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => {
                    setIsDrawerOpen(false);
                    navigation.navigate('MainTabs', { screen: 'BookingHistory' } as any);
                  }}
                  style={styles.drawerLinkItem}
                >
                  <Receipt size={18} color="#059669" style={{ marginRight: 14 }} />
                  <Text style={styles.drawerLinkText}>Riwayat Pemesanan Tiket</Text>
                  <ChevronRight size={16} color="#9CA3AF" />
                </TouchableOpacity>

                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => {
                    setIsDrawerOpen(false);
                    navigation.navigate('Promo');
                  }}
                  style={styles.drawerLinkItem}
                >
                  <Tag size={18} color="#D97706" style={{ marginRight: 14 }} />
                  <Text style={styles.drawerLinkText}>Kupon &amp; Voucher Diskon</Text>
                  <ChevronRight size={16} color="#9CA3AF" />
                </TouchableOpacity>

                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => {
                    setIsDrawerOpen(false);
                    navigation.navigate('MainTabs', { screen: 'Help' } as any);
                  }}
                  style={styles.drawerLinkItem}
                >
                  <HelpCircle size={18} color={COLORS.brandRed} style={{ marginRight: 14 }} />
                  <Text style={styles.drawerLinkText}>Pusat Bantuan &amp; WhatsApp CS</Text>
                  <ChevronRight size={16} color="#9CA3AF" />
                </TouchableOpacity>

                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => {
                    setIsDrawerOpen(false);
                    navigation.navigate('MainTabs', { screen: 'Profile' } as any);
                  }}
                  style={styles.drawerLinkItem}
                >
                  <User size={18} color="#111827" style={{ marginRight: 14 }} />
                  <Text style={styles.drawerLinkText}>Profil &amp; Akun Saya</Text>
                  <ChevronRight size={16} color="#9CA3AF" />
                </TouchableOpacity>
              </ScrollView>

              <View style={styles.drawerFooter}>
                <TouchableOpacity
                  activeOpacity={0.8}
                  onPress={handleLogout}
                  style={styles.drawerLogoutBtn}
                >
                  <LogOut size={16} color={COLORS.brandRed} style={{ marginRight: 8 }} />
                  <Text style={styles.drawerLogoutText}>Keluar Akun</Text>
                </TouchableOpacity>

                <Text style={styles.drawerVersionText}>Tunggal Jaya Transport v2.4.0</Text>
              </View>
            </SafeAreaView>
          </View>
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  safeHeader: {
    backgroundColor: COLORS.bgDark,
    zIndex: 10,
  },
  headerBar: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingVertical: 12,
  },
  headerRight: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
  },
  iconCircle: {
    width: 42,
    height: 42,
    borderRadius: 21,
    backgroundColor: '#FFFFFF',
    borderWidth: 1,
    borderColor: '#E2E8F0',
    justifyContent: 'center',
    alignItems: 'center',
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
  badgeDot: {
    position: 'absolute',
    top: 10,
    right: 11,
    width: 7,
    height: 7,
    borderRadius: 3.5,
    backgroundColor: COLORS.brandRed,
  },
  avatarRing: {
    width: 44,
    height: 44,
    borderRadius: 22,
    borderWidth: 2,
    borderColor: COLORS.brandRed,
    overflow: 'hidden',
  },
  avatarImg: {
    width: '100%',
    height: '100%',
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingTop: 8,
  },
  headlineWrapper: {
    marginVertical: 14,
  },
  headline: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 28,
    color: '#111827',
    lineHeight: 34,
    letterSpacing: -0.5,
  },
  headlineSub: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 24,
    color: '#4B5563',
  },
  locationCapsule: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#FFFFFF',
    borderRadius: 30,
    borderWidth: 1,
    borderColor: '#E2E8F0',
    paddingLeft: 20,
    paddingRight: 6,
    paddingVertical: 6,
    marginBottom: 20,
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.06,
        shadowRadius: 10,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  locationText: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 14,
    color: '#4B5563',
  },
  locationBold: {
    fontFamily: 'PlusJakartaSans_700Bold',
    color: '#111827',
  },
  locationRedBtn: {
    width: 38,
    height: 38,
    borderRadius: 19,
    backgroundColor: COLORS.brandRed,
    justifyContent: 'center',
    alignItems: 'center',
  },
  quickSection: {
    marginBottom: 24,
  },
  sectionHeaderNoMargin: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  sectionTitle: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 18,
    color: '#111827',
    letterSpacing: -0.3,
  },
  sectionSub: {
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 12,
    color: '#6B7280',
  },
  quickGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
    gap: 12,
  },
  quickCard: {
    width: (width - 52) / 2,
    backgroundColor: '#FFFFFF',
    borderRadius: 20,
    borderWidth: 1,
    borderColor: '#E2E8F0',
    paddingVertical: 16,
    paddingHorizontal: 14,
    alignItems: 'center',
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 3 },
        shadowOpacity: 0.05,
        shadowRadius: 8,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  quickIconCircle: {
    width: 48,
    height: 48,
    borderRadius: 24,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 10,
  },
  quickTitle: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 13,
    color: '#111827',
    textAlign: 'center',
    marginBottom: 2,
  },
  quickSubtitle: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 11,
    color: '#6B7280',
    textAlign: 'center',
  },
  heroCardContainer: {
    marginBottom: 26,
  },
  heroCard: {
    borderRadius: 22,
    borderWidth: 1,
    borderColor: '#E2E8F0',
    padding: 20,
    flexDirection: 'row',
    overflow: 'hidden',
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 6 },
        shadowOpacity: 0.08,
        shadowRadius: 14,
      },
      android: {
        elevation: 4,
      },
    }),
  },
  heroLeft: {
    flex: 1.1,
  },
  heroBadges: {
    flexDirection: 'row',
    gap: 6,
    marginBottom: 12,
  },
  heroBadge: {
    backgroundColor: '#EEF2F6',
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 12,
  },
  heroBadgeText: {
    fontFamily: 'PlusJakartaSans_600SemiBold',
    fontSize: 11,
    color: '#4B5563',
  },
  heroTitle: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 21,
    color: '#111827',
    lineHeight: 27,
    marginBottom: 16,
    letterSpacing: -0.4,
  },
  slideBar: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    backgroundColor: '#F1F4F8',
    borderRadius: 20,
    paddingHorizontal: 6,
    paddingVertical: 5,
    width: '95%',
    borderWidth: 1,
    borderColor: '#E2E8F0',
  },
  slideIconBox: {
    width: 32,
    height: 32,
    borderRadius: 16,
    backgroundColor: COLORS.brandRed,
    justifyContent: 'center',
    alignItems: 'center',
  },
  slideArrowText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 12,
    color: '#9CA3AF',
    letterSpacing: 2,
  },
  slideEndIcon: {
    width: 32,
    height: 32,
    borderRadius: 16,
    backgroundColor: 'rgba(230, 0, 35, 0.12)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  heroRight: {
    flex: 0.9,
    justifyContent: 'center',
    alignItems: 'center',
  },
  heroBusImage: {
    width: '120%',
    height: 120,
    transform: [{ scale: 1.15 }],
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 14,
  },
  viewAllBtn: {
    paddingVertical: 4,
    paddingHorizontal: 8,
  },
  viewAllText: {
    fontFamily: 'PlusJakartaSans_600SemiBold',
    fontSize: 13,
    color: COLORS.brandRed,
  },
  whatsNewScroll: {
    gap: 14,
    marginBottom: 26,
  },
  storyCard: {
    width: width * 0.58,
    height: 150,
    borderRadius: 18,
    overflow: 'hidden',
    borderWidth: 1,
    borderColor: '#E2E8F0',
  },
  storyImage: {
    width: '100%',
    height: '100%',
  },
  storyGradient: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
    padding: 14,
  },
  storyTitle: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 14,
    color: '#FFFFFF',
    marginBottom: 2,
  },
  storySubtitle: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 11,
    color: 'rgba(255, 255, 255, 0.8)',
    marginBottom: 8,
  },
  storyPill: {
    alignSelf: 'flex-start',
    backgroundColor: 'rgba(255, 255, 255, 0.25)',
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 10,
  },
  storyPillText: {
    fontFamily: 'PlusJakartaSans_600SemiBold',
    fontSize: 10,
    color: '#FFFFFF',
  },
  scheduleList: {
    gap: 12,
    marginBottom: 26,
  },
  scheduleCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#FFFFFF',
    borderRadius: 18,
    borderWidth: 1,
    borderColor: '#E2E8F0',
    padding: 12,
    gap: 14,
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 3 },
        shadowOpacity: 0.05,
        shadowRadius: 8,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  scheduleBusThumb: {
    width: 68,
    height: 68,
    borderRadius: 14,
  },
  scheduleMiddle: {
    flex: 1,
  },
  scheduleRoute: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 15,
    color: '#111827',
    marginBottom: 2,
  },
  scheduleBusName: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 12,
    color: '#4B5563',
    marginBottom: 6,
  },
  schedulePrice: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 15,
    color: '#111827',
  },
  schedulePerPerson: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 11,
    color: '#6B7280',
  },
  scheduleAction: {
    alignItems: 'flex-end',
    gap: 8,
  },
  ratingBadge: {
    backgroundColor: 'rgba(217, 119, 6, 0.1)',
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 8,
  },
  ratingText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 11,
    color: '#D97706',
  },
  chevronCircle: {
    width: 32,
    height: 32,
    borderRadius: 16,
    backgroundColor: COLORS.brandRed,
    justifyContent: 'center',
    alignItems: 'center',
  },
  amenitiesCard: {
    backgroundColor: '#FFFFFF',
    borderRadius: 18,
    padding: 18,
    borderWidth: 1,
    borderColor: '#E2E8F0',
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 3 },
        shadowOpacity: 0.04,
        shadowRadius: 8,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  amenitiesTitle: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 15,
    color: '#111827',
    marginBottom: 14,
  },
  amenitiesGrid: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  amenityItem: {
    alignItems: 'center',
    gap: 6,
  },
  amenityIconCircle: {
    width: 36,
    height: 36,
    borderRadius: 18,
    backgroundColor: 'rgba(230, 0, 35, 0.08)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  amenityText: {
    fontFamily: 'PlusJakartaSans_600SemiBold',
    fontSize: 11,
    color: '#4B5563',
  },
  modalOverlay: {
    flex: 1,
    flexDirection: 'row',
  },
  modalBackdrop: {
    position: 'absolute',
    top: 0,
    bottom: 0,
    left: 0,
    right: 0,
    backgroundColor: 'rgba(17, 24, 39, 0.5)',
  },
  drawerContainer: {
    width: '80%',
    maxWidth: 320,
    backgroundColor: '#FFFFFF',
    height: '100%',
    ...Platform.select({
      ios: {
        shadowColor: '#000',
        shadowOffset: { width: 6, height: 0 },
        shadowOpacity: 0.15,
        shadowRadius: 18,
      },
      android: {
        elevation: 16,
      },
    }),
  },
  drawerContent: {
    flex: 1,
    justifyContent: 'space-between',
    paddingVertical: 16,
  },
  drawerHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-start',
    paddingHorizontal: 20,
    paddingBottom: 20,
    borderBottomWidth: 1,
    borderBottomColor: '#E2E8F0',
  },
  drawerUserRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
    flex: 1,
  },
  drawerAvatar: {
    width: 46,
    height: 46,
    borderRadius: 23,
    backgroundColor: '#F1F4F8',
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#E2E8F0',
  },
  drawerUserInfo: {
    flex: 1,
  },
  drawerUserName: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 16,
    color: '#111827',
  },
  drawerUserEmail: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 12,
    color: '#6B7280',
    marginBottom: 4,
  },
  vipBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    alignSelf: 'flex-start',
    backgroundColor: 'rgba(217, 119, 6, 0.12)',
    paddingHorizontal: 8,
    paddingVertical: 2,
    borderRadius: 10,
  },
  vipBadgeText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 10,
    color: '#D97706',
  },
  drawerCloseBtn: {
    width: 36,
    height: 36,
    borderRadius: 18,
    backgroundColor: '#F1F4F8',
    justifyContent: 'center',
    alignItems: 'center',
  },
  drawerLinks: {
    flex: 1,
    paddingHorizontal: 16,
    paddingTop: 16,
  },
  drawerLinkItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 14,
    paddingHorizontal: 12,
    borderRadius: 14,
    marginBottom: 4,
  },
  drawerLinkText: {
    flex: 1,
    fontFamily: 'PlusJakartaSans_600SemiBold',
    fontSize: 14,
    color: '#111827',
  },
  drawerFooter: {
    paddingHorizontal: 20,
    paddingTop: 16,
    borderTopWidth: 1,
    borderTopColor: '#E2E8F0',
  },
  drawerLogoutBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#FEE2E2',
    height: 46,
    borderRadius: 23,
    marginBottom: 12,
  },
  drawerLogoutText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 13,
    color: COLORS.brandRed,
  },
  drawerVersionText: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 11,
    color: '#9CA3AF',
    textAlign: 'center',
  },
});
