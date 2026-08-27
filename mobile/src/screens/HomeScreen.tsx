import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Image,
  Dimensions,
  ActivityIndicator,
  RefreshControl,
  FlatList,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { Colors, Radius } from '../theme/colors';
import { useAuth } from '../context/AuthContext';
import api from '../api/client';
import {
  Menu,
  Bell,
  MapPin,
  ArrowRight,
  Sparkles,
  Bus,
  Tag,
  Star,
  Clock,
  ChevronRight,
  ShieldCheck,
  Wifi,
  Tv,
  Coffee,
} from 'lucide-react-native';

const { width: SCREEN_WIDTH } = Dimensions.get('window');

export default function HomeScreen({ navigation }: any) {
  const insets = useSafeAreaInsets();
  const { user } = useAuth();

  const [selectedCategory, setSelectedCategory] = useState('bus');
  const [schedules, setSchedules] = useState<any[]>([]);
  const [news, setNews] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const categories = [
    { id: 'bus', title: 'Bus Tickets', icon: Bus },
    { id: 'charter', title: 'Sewa Pariwisata', icon: Sparkles },
    { id: 'promo', title: 'Promo & Kupon', icon: Tag },
  ];

  const fetchHomeData = async () => {
    try {
      setLoading(true);
      const [schedulesRes, newsRes] = await Promise.all([
        api.get('/schedules').catch(() => ({ data: { data: [] } })),
        api.get('/news').catch(() => ({ data: { data: [] } })),
      ]);

      setSchedules(schedulesRes.data?.data || []);
      setNews(newsRes.data?.data || []);
    } catch (e) {
      console.error('Error fetching home data:', e);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  useEffect(() => {
    fetchHomeData();
  }, []);

  const onRefresh = () => {
    setRefreshing(true);
    fetchHomeData();
  };

  // Helper check if departed today
  const isBusDeparted = (departureTimeStr: string) => {
    try {
      if (!departureTimeStr) return false;
      const parts = departureTimeStr.split(':');
      if (parts.length < 2) return false;
      const now = new Date();
      const dep = new Date();
      dep.setHours(parseInt(parts[0], 10), parseInt(parts[1], 10), 0, 0);
      return now > dep;
    } catch {
      return false;
    }
  };

  return (
    <View style={styles.container}>
      <ScrollView
        contentContainerStyle={[
          styles.scrollContent,
          { paddingTop: insets.top + 12, paddingBottom: 110 },
        ]}
        showsVerticalScrollIndicator={false}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={Colors.primary} />
        }
      >
        {/* Top App Bar (Explore / Menu / Notification / Avatar) */}
        <View style={styles.topBar}>
          <View style={styles.leftTopBar}>
            <TouchableOpacity style={styles.iconCircleBtn} activeOpacity={0.7}>
              <Menu size={18} color="#FFFFFF" />
            </TouchableOpacity>

            <TouchableOpacity
              style={styles.avatarContainer}
              onPress={() => navigation.navigate('Profile')}
              activeOpacity={0.8}
            >
              <Image
                source={require('../../assets/logo/logoNoBg.png')}
                style={styles.avatarImg}
                resizeMode="contain"
              />
            </TouchableOpacity>
          </View>

          <TouchableOpacity style={styles.iconCircleBtn} activeOpacity={0.7}>
            <Bell size={18} color="#FFFFFF" />
            <View style={styles.notificationBadge} />
          </TouchableOpacity>
        </View>

        {/* Explore Headline */}
        <View style={styles.headlineSection}>
          <Text style={styles.exploreTitle}>Explore</Text>
          <Text style={styles.exploreSubtitle}>Go Everywhere?</Text>
        </View>

        {/* Location Search Bar Capsule */}
        <TouchableOpacity
          style={styles.searchCapsule}
          onPress={() => navigation.navigate('ScheduleList')}
          activeOpacity={0.9}
        >
          <View style={styles.searchContent}>
            <Text style={styles.searchFromText}>Kuningan, Jawa Barat</Text>
            <View style={styles.searchRouteRow}>
              <Text style={styles.searchToText}>Jakarta / Jabodetabek</Text>
              <Text style={styles.searchDateText}>• Hari Ini</Text>
            </View>
          </View>

          <View style={styles.searchPinButton}>
            <MapPin size={18} color="#FFFFFF" />
          </View>
        </TouchableOpacity>

        {/* Category Filter Chips */}
        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={styles.categoryChipsScroll}
        >
          {categories.map((cat) => {
            const isSelected = selectedCategory === cat.id;
            const IconComp = cat.icon;
            return (
              <TouchableOpacity
                key={cat.id}
                style={[styles.categoryChip, isSelected && styles.categoryChipActive]}
                onPress={() => {
                  setSelectedCategory(cat.id);
                  if (cat.id === 'charter') navigation.navigate('Charter');
                  if (cat.id === 'promo') navigation.navigate('Promo');
                }}
                activeOpacity={0.8}
              >
                <IconComp
                  size={15}
                  color={isSelected ? '#FFFFFF' : Colors.textSecondary}
                  style={{ marginRight: 8 }}
                />
                <Text
                  style={[styles.categoryChipText, isSelected && styles.categoryChipTextActive]}
                >
                  {cat.title}
                </Text>
              </TouchableOpacity>
            );
          })}
        </ScrollView>

        {/* Featured Bus Hero Card */}
        <View style={styles.heroCard}>
          <View style={styles.heroHeaderRow}>
            <View style={styles.routeTag}>
              <Text style={styles.routeTagText}>Kuningan ➔ Jakarta</Text>
            </View>
            <View style={styles.durationTag}>
              <Clock size={12} color="#FFFFFF" style={{ marginRight: 4 }} />
              <Text style={styles.durationText}>± 4-5 Jam</Text>
            </View>
          </View>

          <Text style={styles.heroBusTitle}>Executive Suite Class</Text>
          <Text style={styles.heroBusDesc}>Air Suspension • Leg Rest • Snack & Drink</Text>

          {/* Cutout Bus Image */}
          <View style={styles.heroBusImageContainer}>
            <Image
              source={require('../../assets/images/heroImg.jpg')}
              style={styles.heroBusImage}
              resizeMode="cover"
            />
          </View>

          {/* Slide Action Bar */}
          <TouchableOpacity
            style={styles.heroActionPill}
            onPress={() => navigation.navigate('ScheduleList')}
            activeOpacity={0.85}
          >
            <Text style={styles.heroActionText}>Pesan Tiket Sekarang</Text>
            <View style={styles.heroActionCircle}>
              <ArrowRight size={16} color="#FFFFFF" />
            </View>
          </TouchableOpacity>
        </View>

        {/* "What's New" Stories / News Banner Carousel */}
        <View style={styles.sectionHeaderRow}>
          <Text style={styles.sectionTitle}>What's New</Text>
          <TouchableOpacity onPress={() => navigation.navigate('Promo')}>
            <Text style={styles.sectionViewAll}>View all &gt;</Text>
          </TouchableOpacity>
        </View>

        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={styles.storiesScroll}
        >
          {news.length > 0 ? (
            news.map((item, idx) => (
              <TouchableOpacity key={idx} style={styles.storyCard} activeOpacity={0.85}>
                <Image
                  source={require('../../assets/images/heroImg.jpg')}
                  style={styles.storyImage}
                  resizeMode="cover"
                />
                <View style={styles.storyOverlay}>
                  <Text style={styles.storyTag}>BERITA</Text>
                  <Text style={styles.storyTitle} numberOfLines={2}>
                    {item.title}
                  </Text>
                </View>
              </TouchableOpacity>
            ))
          ) : (
            <View style={styles.storyCard}>
              <Image
                source={require('../../assets/images/heroImg.jpg')}
                style={styles.storyImage}
                resizeMode="cover"
              />
              <View style={styles.storyOverlay}>
                <Text style={styles.storyTag}>PROMO</Text>
                <Text style={styles.storyTitle}>Diskon Spesial Liburan 20%</Text>
              </View>
            </View>
          )}
        </ScrollView>

        {/* Popular Schedules Section */}
        <View style={styles.sectionHeaderRow}>
          <Text style={styles.sectionTitle}>Jadwal Bus Terpopuler</Text>
          <TouchableOpacity onPress={() => navigation.navigate('ScheduleList')}>
            <Text style={styles.sectionViewAll}>Semua Jadwal &gt;</Text>
          </TouchableOpacity>
        </View>

        {loading ? (
          <ActivityIndicator color={Colors.primary} style={{ marginTop: 20 }} />
        ) : (
          schedules.slice(0, 3).map((item, idx) => {
            const departed = isBusDeparted(item.departure_time);
            return (
              <View key={idx} style={styles.scheduleCard}>
                <View style={styles.scheduleHeaderRow}>
                  <View style={styles.scheduleBusBadge}>
                    <Bus size={13} color={Colors.primary} style={{ marginRight: 5 }} />
                    <Text style={styles.scheduleBusName}>
                      {item.bus?.name || 'Tunggal Jaya Bus'}
                    </Text>
                  </View>

                  <View
                    style={[
                      styles.statusBadge,
                      departed ? styles.statusDeparted : styles.statusAvailable,
                    ]}
                  >
                    <Text
                      style={[
                        styles.statusBadgeText,
                        departed ? styles.statusDepartedText : styles.statusAvailableText,
                      ]}
                    >
                      {departed ? 'Sudah Berangkat' : 'Tersedia'}
                    </Text>
                  </View>
                </View>

                {/* Route Flow */}
                <View style={styles.scheduleRouteRow}>
                  <View style={styles.routeCol}>
                    <Text style={styles.routeTime}>
                      {item.departure_time ? item.departure_time.slice(0, 5) : '07:00'}
                    </Text>
                    <Text style={styles.routeName}>
                      {item.route?.origin_city || 'Kuningan'}
                    </Text>
                  </View>

                  <View style={styles.routeDivider}>
                    <View style={styles.dividerLine} />
                    <View style={styles.busDot}>
                      <Bus size={10} color={Colors.primary} />
                    </View>
                    <View style={styles.dividerLine} />
                  </View>

                  <View style={[styles.routeCol, { alignItems: 'flex-end' }]}>
                    <Text style={styles.routeTime}>
                      {item.arrival_time ? item.arrival_time.slice(0, 5) : '12:00'}
                    </Text>
                    <Text style={styles.routeName}>
                      {item.route?.destination_city || 'Jakarta'}
                    </Text>
                  </View>
                </View>

                {/* Price & Book Action */}
                <View style={styles.scheduleFooterRow}>
                  <View>
                    <Text style={styles.priceLabel}>Mulai dari</Text>
                    <Text style={styles.priceValue}>
                      Rp {Number(item.price || 130000).toLocaleString('id-ID')}
                    </Text>
                  </View>

                  <TouchableOpacity
                    style={[styles.bookBtn, departed && styles.bookBtnDisabled]}
                    disabled={departed}
                    onPress={() =>
                      navigation.navigate('SeatSelection', {
                        schedule: item,
                        date: new Date().toISOString().split('T')[0],
                      })
                    }
                    activeOpacity={0.8}
                  >
                    <Text style={styles.bookBtnText}>
                      {departed ? 'Berangkat' : 'Pilih Kursi'}
                    </Text>
                  </TouchableOpacity>
                </View>
              </View>
            );
          })
        )}

        {/* Fleet Amenities */}
        <View style={styles.amenitiesSection}>
          <Text style={styles.amenitiesTitle}>Fasilitas Armada Unggulan</Text>
          <View style={styles.amenitiesGrid}>
            <View style={styles.amenityItem}>
              <Wifi size={20} color={Colors.primary} />
              <Text style={styles.amenityText}>Free Wi-Fi</Text>
            </View>
            <View style={styles.amenityItem}>
              <Tv size={20} color={Colors.primary} />
              <Text style={styles.amenityText}>Personal AVOD</Text>
            </View>
            <View style={styles.amenityItem}>
              <Coffee size={20} color={Colors.primary} />
              <Text style={styles.amenityText}>Snack & Drink</Text>
            </View>
            <View style={styles.amenityItem}>
              <ShieldCheck size={20} color={Colors.primary} />
              <Text style={styles.amenityText}>Air Purifier</Text>
            </View>
          </View>
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
  scrollContent: {
    paddingHorizontal: 20,
  },
  topBar: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 16,
  },
  leftTopBar: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
  },
  iconCircleBtn: {
    width: 42,
    height: 42,
    backgroundColor: Colors.surfaceCard,
    borderRadius: Radius.full,
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 1.2,
    borderColor: Colors.border,
    position: 'relative',
  },
  notificationBadge: {
    position: 'absolute',
    top: 9,
    right: 10,
    width: 8,
    height: 8,
    borderRadius: Radius.full,
    backgroundColor: Colors.primary,
  },
  avatarContainer: {
    width: 42,
    height: 42,
    backgroundColor: Colors.surfaceContainer,
    borderRadius: Radius.full,
    borderWidth: 1.5,
    borderColor: Colors.primary,
    overflow: 'hidden',
    justifyContent: 'center',
    alignItems: 'center',
  },
  avatarImg: {
    width: 26,
    height: 26,
  },
  headlineSection: {
    marginBottom: 18,
  },
  exploreTitle: {
    fontSize: 32,
    fontWeight: '900',
    color: '#FFFFFF',
    letterSpacing: -1,
  },
  exploreSubtitle: {
    fontSize: 22,
    fontWeight: '800',
    color: '#FFFFFF',
    letterSpacing: -0.5,
    marginTop: -4,
  },
  searchCapsule: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: Colors.surfaceCard,
    borderRadius: Radius.pill,
    paddingLeft: 20,
    paddingRight: 6,
    paddingVertical: 8,
    borderWidth: 1.2,
    borderColor: Colors.border,
    shadowColor: '#000000',
    shadowOffset: { width: 0, height: 6 },
    shadowOpacity: 0.4,
    shadowRadius: 18,
    elevation: 6,
    marginBottom: 20,
  },
  searchContent: {
    flex: 1,
  },
  searchFromText: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '800',
  },
  searchRouteRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 2,
  },
  searchToText: {
    color: Colors.textSecondary,
    fontSize: 12,
    fontWeight: '500',
  },
  searchDateText: {
    color: Colors.primary,
    fontSize: 12,
    fontWeight: '700',
    marginLeft: 4,
  },
  searchPinButton: {
    width: 44,
    height: 44,
    borderRadius: Radius.full,
    backgroundColor: Colors.primary,
    justifyContent: 'center',
    alignItems: 'center',
    shadowColor: Colors.primary,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.4,
    shadowRadius: 10,
    elevation: 4,
  },
  categoryChipsScroll: {
    flexDirection: 'row',
    gap: 10,
    marginBottom: 24,
  },
  categoryChip: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: Colors.surfaceCard,
    paddingHorizontal: 16,
    paddingVertical: 10,
    borderRadius: Radius.pill,
    borderWidth: 1.2,
    borderColor: Colors.border,
  },
  categoryChipActive: {
    backgroundColor: '#26263A',
    borderColor: Colors.primary,
  },
  categoryChipText: {
    color: Colors.textSecondary,
    fontSize: 13,
    fontWeight: '600',
  },
  categoryChipTextActive: {
    color: '#FFFFFF',
    fontWeight: '800',
  },
  heroCard: {
    backgroundColor: Colors.surfaceCard,
    borderRadius: 28,
    borderWidth: 1.2,
    borderColor: Colors.border,
    padding: 22,
    marginBottom: 26,
    shadowColor: '#000000',
    shadowOffset: { width: 0, height: 10 },
    shadowOpacity: 0.5,
    shadowRadius: 28,
    elevation: 8,
  },
  heroHeaderRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 10,
  },
  routeTag: {
    backgroundColor: Colors.surfaceContainer,
    paddingHorizontal: 12,
    paddingVertical: 5,
    borderRadius: Radius.pill,
    borderWidth: 1,
    borderColor: Colors.borderLight,
  },
  routeTagText: {
    color: '#FFFFFF',
    fontSize: 12,
    fontWeight: '800',
  },
  durationTag: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: Colors.primaryContainer,
    paddingHorizontal: 10,
    paddingVertical: 5,
    borderRadius: Radius.pill,
  },
  durationText: {
    color: Colors.primary,
    fontSize: 11,
    fontWeight: '800',
  },
  heroBusTitle: {
    fontSize: 20,
    fontWeight: '900',
    color: '#FFFFFF',
  },
  heroBusDesc: {
    fontSize: 12,
    color: Colors.textSecondary,
    marginTop: 2,
    marginBottom: 16,
  },
  heroBusImageContainer: {
    height: 140,
    borderRadius: 18,
    overflow: 'hidden',
    marginBottom: 16,
  },
  heroBusImage: {
    width: '100%',
    height: '100%',
  },
  heroActionPill: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: Colors.primary,
    paddingLeft: 20,
    paddingRight: 6,
    paddingVertical: 6,
    borderRadius: Radius.pill,
  },
  heroActionText: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '800',
  },
  heroActionCircle: {
    width: 36,
    height: 36,
    borderRadius: Radius.full,
    backgroundColor: 'rgba(0, 0, 0, 0.25)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  sectionHeaderRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 14,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: '800',
    color: '#FFFFFF',
  },
  sectionViewAll: {
    fontSize: 12,
    color: Colors.primary,
    fontWeight: '700',
  },
  storiesScroll: {
    flexDirection: 'row',
    gap: 14,
    marginBottom: 26,
  },
  storyCard: {
    width: SCREEN_WIDTH * 0.65,
    height: 120,
    borderRadius: 20,
    overflow: 'hidden',
    position: 'relative',
    borderWidth: 1.2,
    borderColor: Colors.border,
  },
  storyImage: {
    width: '100%',
    height: '100%',
  },
  storyOverlay: {
    ...StyleSheet.absoluteFillObject,
    backgroundColor: 'rgba(0, 0, 0, 0.55)',
    padding: 14,
    justifyContent: 'flex-end',
  },
  storyTag: {
    color: Colors.primary,
    fontSize: 10,
    fontWeight: '900',
    letterSpacing: 0.5,
    marginBottom: 2,
  },
  storyTitle: {
    color: '#FFFFFF',
    fontSize: 13,
    fontWeight: '800',
    lineHeight: 18,
  },
  scheduleCard: {
    backgroundColor: Colors.surfaceCard,
    borderRadius: 22,
    borderWidth: 1.2,
    borderColor: Colors.border,
    padding: 18,
    marginBottom: 14,
  },
  scheduleHeaderRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 14,
  },
  scheduleBusBadge: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  scheduleBusName: {
    color: '#FFFFFF',
    fontSize: 13,
    fontWeight: '800',
  },
  statusBadge: {
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: Radius.pill,
  },
  statusBadgeText: {
    fontSize: 11,
    fontWeight: '800',
  },
  statusAvailable: {
    backgroundColor: Colors.successContainer,
  },
  statusAvailableText: {
    color: Colors.success,
    fontSize: 11,
    fontWeight: '800',
  },
  statusDeparted: {
    backgroundColor: Colors.surfaceContainer,
  },
  statusDepartedText: {
    color: Colors.textMuted,
    fontSize: 11,
    fontWeight: '700',
  },
  scheduleRouteRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 16,
  },
  routeCol: {
    flex: 1,
  },
  routeTime: {
    color: '#FFFFFF',
    fontSize: 18,
    fontWeight: '900',
  },
  routeName: {
    color: Colors.textSecondary,
    fontSize: 12,
    marginTop: 2,
  },
  routeDivider: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 0.8,
    justifyContent: 'center',
  },
  dividerLine: {
    flex: 1,
    height: 1,
    backgroundColor: Colors.borderLight,
  },
  busDot: {
    marginHorizontal: 8,
  },
  scheduleFooterRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingTop: 12,
    borderTopWidth: 1,
    borderTopColor: Colors.border,
  },
  priceLabel: {
    color: Colors.textMuted,
    fontSize: 11,
  },
  priceValue: {
    color: '#FFFFFF',
    fontSize: 16,
    fontWeight: '900',
  },
  bookBtn: {
    backgroundColor: Colors.primary,
    paddingHorizontal: 18,
    paddingVertical: 10,
    borderRadius: Radius.pill,
  },
  bookBtnDisabled: {
    backgroundColor: Colors.surfaceHighest,
  },
  bookBtnText: {
    color: '#FFFFFF',
    fontSize: 13,
    fontWeight: '800',
  },
  amenitiesSection: {
    backgroundColor: Colors.surfaceCard,
    borderRadius: 22,
    borderWidth: 1.2,
    borderColor: Colors.border,
    padding: 18,
    marginTop: 10,
  },
  amenitiesTitle: {
    fontSize: 14,
    fontWeight: '800',
    color: '#FFFFFF',
    marginBottom: 14,
  },
  amenitiesGrid: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  amenityItem: {
    alignItems: 'center',
  },
  amenityText: {
    color: Colors.textSecondary,
    fontSize: 11,
    marginTop: 6,
    fontWeight: '600',
  },
});
