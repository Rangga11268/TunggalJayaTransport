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
  Sparkles,
  CreditCard,
  ArrowRight,
  ShieldCheck,
  Wifi,
  Tv,
  Coffee,
} from 'lucide-react-native';
import { RootStackParamList } from '../navigation/RootNavigator';
import { COLORS } from '../theme/colors';
import { useAuth } from '../context/AuthContext';
import apiClient from '../api/client';

const { width } = Dimensions.get('window');

type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

export default function HomeScreen() {
  const navigation = useNavigation<NavigationProp>();
  const { user } = useAuth();

  const [activeCategory, setActiveCategory] = useState('bus');
  const [schedules, setSchedules] = useState<any[]>([]);
  const [articles, setArticles] = useState<any[]>([]);
  const [refreshing, setRefreshing] = useState(false);
  const [selectedOrigin, setSelectedOrigin] = useState('Jakarta');
  const [selectedDestination, setSelectedDestination] = useState('Kuningan');

  const categories = [
    { id: 'bus', label: 'Bus Tickets', icon: Bus },
    { id: 'charter', label: 'Pariwisata', icon: Compass },
    { id: 'promo', label: 'Vouchers', icon: Ticket },
  ];

  const fetchHomeData = async () => {
    try {
      const [schedRes, newsRes] = await Promise.allSettled([
        apiClient.get('/schedules'),
        apiClient.get('/news'),
      ]);

      if (schedRes.status === 'fulfilled' && schedRes.value.data) {
        const list = Array.isArray(schedRes.value.data)
          ? schedRes.value.data
          : schedRes.value.data.data || [];
        setSchedules(list.slice(0, 4));
      }

      if (newsRes.status === 'fulfilled' && newsRes.value.data) {
        const list = Array.isArray(newsRes.value.data)
          ? newsRes.value.data
          : newsRes.value.data.data || [];
        setArticles(list.slice(0, 3));
      }
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

  return (
    <View style={styles.container}>
      <SafeAreaView edges={['top']} style={styles.safeHeader}>
        {/* Top App Bar */}
        <View style={styles.headerBar}>
          <TouchableOpacity activeOpacity={0.7} style={styles.iconCircle}>
            <Menu size={20} color="#111827" />
          </TouchableOpacity>

          <View style={styles.headerRight}>
            <TouchableOpacity activeOpacity={0.7} style={styles.iconCircle}>
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
          onPress={() => navigation.navigate('Schedules', { origin: selectedOrigin, destination: selectedDestination })}
          style={styles.locationCapsule}
        >
          <Text style={styles.locationText}>
            Current: <Text style={styles.locationBold}>{selectedOrigin} → {selectedDestination}</Text>
          </Text>
          <View style={styles.locationRedBtn}>
            <MapPin size={16} color="#FFFFFF" />
          </View>
        </TouchableOpacity>

        {/* Category Filter Chips */}
        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={styles.categoriesRow}
        >
          {categories.map((cat) => {
            const isActive = activeCategory === cat.id;
            const IconComp = cat.icon;
            return (
              <TouchableOpacity
                key={cat.id}
                activeOpacity={0.8}
                onPress={() => {
                  setActiveCategory(cat.id);
                  if (cat.id === 'charter') navigation.navigate('Charter');
                  if (cat.id === 'promo') navigation.navigate('Promo');
                }}
                style={[styles.categoryChip, isActive ? styles.categoryChipActive : styles.categoryChipInactive]}
              >
                <IconComp size={16} color={isActive ? '#FFFFFF' : '#4B5563'} />
                <Text style={[styles.categoryText, isActive ? styles.categoryTextActive : styles.categoryTextInactive]}>
                  {cat.label}
                </Text>
              </TouchableOpacity>
            );
          })}
        </ScrollView>

        {/* Featured Hero Card (Warm Alabaster Light Luxury) */}
        <View style={styles.heroCardContainer}>
          <LinearGradient
            colors={['#FFFFFF', '#F8FAFC']}
            style={styles.heroCard}
          >
            {/* Left Content */}
            <View style={styles.heroLeft}>
              <View style={styles.heroBadges}>
                <View style={styles.heroBadge}>
                  <Text style={styles.heroBadgeText}>Central Line</Text>
                </View>
                <View style={styles.heroBadge}>
                  <Text style={styles.heroBadgeText}>8-10 hrs</Text>
                </View>
              </View>

              <Text style={styles.heroTitle}>
                Executive{'\n'}Double Decker
              </Text>

              {/* Slider Button */}
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

            {/* Right Bus Cutout Image */}
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
          {/* Card 1: Executive Experience */}
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
              <Text style={styles.storyTitle}>Dive into Luxury Journey</Text>
              <Text style={styles.storySubtitle}>Armada baru sleeper bus eksekutif</Text>
              <View style={styles.storyPill}>
                <Text style={styles.storyPillText}>View &gt;</Text>
              </View>
            </LinearGradient>
          </TouchableOpacity>

          {/* Card 2: Pariwisata Charter */}
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
              <Text style={styles.storyTitle}>Sewa Bus Pariwisata</Text>
              <Text style={styles.storySubtitle}>Rute fleksibel &amp; harga spesial</Text>
              <View style={styles.storyPill}>
                <Text style={styles.storyPillText}>View &gt;</Text>
              </View>
            </LinearGradient>
          </TouchableOpacity>
        </ScrollView>

        {/* Popular Routes List */}
        <View style={styles.sectionHeader}>
          <Text style={styles.sectionTitle}>Popular Routes</Text>
          <TouchableOpacity
            activeOpacity={0.7}
            onPress={() => navigation.navigate('Schedules')}
            style={styles.viewAllBtn}
          >
            <Text style={styles.viewAllText}>All &gt;</Text>
          </TouchableOpacity>
        </View>

        <View style={styles.scheduleList}>
          {schedules.map((item, idx) => {
            const busName = item.bus?.name || 'Resi Bisma';
            const origin = item.route?.origin || 'Jakarta';
            const destination = item.route?.destination || 'Kuningan';
            const price = Number(item.price || 180000).toLocaleString('id-ID');
            const depTime = item.departure_time ? item.departure_time.substring(0, 5) : '07:00';

            return (
              <TouchableOpacity
                key={item.id || idx}
                activeOpacity={0.85}
                onPress={() => navigation.navigate('ScheduleDetail', { scheduleId: item.id })}
                style={styles.scheduleCard}
              >
                <Image
                  source={idx % 2 === 0 ? require('../../assets/images/primadona.webp') : require('../../assets/images/bentas01.webp')}
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
                    <Text style={styles.ratingText}>★ 4.8</Text>
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
          <Text style={styles.amenitiesTitle}>Fasilitas Standar Eksekutif</Text>
          <View style={styles.amenitiesGrid}>
            <View style={styles.amenityItem}>
              <View style={styles.amenityIconCircle}>
                <Wifi size={16} color={COLORS.brandRed} />
              </View>
              <Text style={styles.amenityText}>Free 4G Wi-Fi</Text>
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
              <Text style={styles.amenityText}>Snack &amp; Air</Text>
            </View>
            <View style={styles.amenityItem}>
              <View style={styles.amenityIconCircle}>
                <ShieldCheck size={16} color={COLORS.brandRed} />
              </View>
              <Text style={styles.amenityText}>Kru Resmi</Text>
            </View>
          </View>
        </View>

        {/* Bottom Spacing for Floating Tab Bar */}
        <View style={{ height: 110 }} />
      </ScrollView>
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
  categoriesRow: {
    flexDirection: 'row',
    gap: 10,
    marginBottom: 22,
  },
  categoryChip: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    paddingHorizontal: 16,
    paddingVertical: 10,
    borderRadius: 24,
  },
  categoryChipActive: {
    backgroundColor: COLORS.brandRed,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandRed,
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.35,
        shadowRadius: 8,
      },
      android: {
        elevation: 4,
      },
    }),
  },
  categoryChipInactive: {
    backgroundColor: '#FFFFFF',
    borderWidth: 1,
    borderColor: '#E2E8F0',
  },
  categoryText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 13,
  },
  categoryTextActive: {
    color: '#FFFFFF',
  },
  categoryTextInactive: {
    color: '#4B5563',
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
    fontSize: 22,
    color: '#111827',
    lineHeight: 28,
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
  sectionTitle: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 18,
    color: '#111827',
    letterSpacing: -0.3,
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
});
