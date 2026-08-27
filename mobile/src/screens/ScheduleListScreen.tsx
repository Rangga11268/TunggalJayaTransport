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
  ActivityIndicator,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { useNavigation, useRoute } from '@react-navigation/native';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';
import {
  ArrowLeft,
  Bell,
  Star,
  ChevronRight,
  User,
  Clock,
  ShieldCheck,
  CheckCircle,
  Tag,
  FileText,
  MessageSquare,
} from 'lucide-react-native';
import { RootStackParamList } from '../navigation/RootNavigator';
import { COLORS } from '../theme/colors';
import apiClient from '../api/client';

const { width } = Dimensions.get('window');

type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

export default function ScheduleListScreen() {
  const navigation = useNavigation<NavigationProp>();
  const route = useRoute<any>();

  const [activeTab, setActiveTab] = useState<'deals' | 'details' | 'reviews'>('deals');
  const [schedules, setSchedules] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [selectedSchedule, setSelectedSchedule] = useState<any>(null);

  useEffect(() => {
    fetchSchedules();
  }, []);

  const fetchSchedules = async () => {
    try {
      setLoading(true);
      const res = await apiClient.get('/schedules');
      const list = Array.isArray(res.data) ? res.data : res.data.data || [];
      setSchedules(list);
      if (list.length > 0) {
        setSelectedSchedule(list[0]);
      }
    } catch (e) {
      console.log('Error fetching schedules:', e);
    } finally {
      setLoading(false);
    }
  };

  const tabs = [
    { id: 'deals', label: 'Deals', icon: Tag },
    { id: 'details', label: 'Details', icon: FileText },
    { id: 'reviews', label: 'Reviews', icon: MessageSquare },
  ];

  return (
    <View style={styles.container}>
      {/* Top Hero Photo */}
      <View style={styles.heroContainer}>
        <Image
          source={require('../../assets/images/heroImg.jpg')}
          style={styles.heroImage}
          resizeMode="cover"
        />
        <LinearGradient
          colors={['rgba(10, 12, 16, 0.7)', 'transparent', 'rgba(10, 12, 16, 0.95)', '#0A0C10']}
          locations={[0, 0.3, 0.75, 1]}
          style={styles.heroGradient}
        >
          {/* Header Navigation Bar */}
          <SafeAreaView edges={['top']} style={styles.navBar}>
            <TouchableOpacity
              activeOpacity={0.7}
              onPress={() => navigation.goBack()}
              style={styles.navIconBtn}
            >
              <ArrowLeft size={18} color="#FFFFFF" />
            </TouchableOpacity>

            <Text style={styles.navTitle}>Schedule Detail</Text>

            <TouchableOpacity activeOpacity={0.7} style={styles.navIconBtn}>
              <Bell size={18} color="#FFFFFF" />
            </TouchableOpacity>
          </SafeAreaView>

          {/* Floating Route Info Overlay */}
          <View style={styles.routeHeaderOverlay}>
            <View style={styles.routeHeaderLeft}>
              <Text style={styles.routeHeaderTitle}>Central Line</Text>
              <Text style={styles.routeHeaderSub}>Jakarta ↔ Kuningan</Text>
            </View>

            <View style={styles.routeHeaderRight}>
              <View style={styles.starsRow}>
                <Star size={14} color="#FFB800" fill="#FFB800" />
                <Text style={styles.starsText}>4.8 (9.6k)</Text>
              </View>
              <Text style={styles.routeHeaderPrice}>
                Rp 180.000 <Text style={styles.routeHeaderPriceSub}>/ org</Text>
              </Text>
            </View>
          </View>
        </LinearGradient>
      </View>

      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContent}
      >
        {/* Tab Switcher Pills */}
        <View style={styles.tabsRow}>
          {tabs.map((t) => {
            const isActive = activeTab === t.id;
            const IconComp = t.icon;
            return (
              <TouchableOpacity
                key={t.id}
                activeOpacity={0.8}
                onPress={() => setActiveTab(t.id as any)}
                style={[styles.tabPill, isActive ? styles.tabPillActive : styles.tabPillInactive]}
              >
                <IconComp size={15} color={isActive ? '#FFFFFF' : COLORS.textSecondary} />
                <Text style={[styles.tabPillText, isActive ? styles.tabPillTextActive : styles.tabPillTextInactive]}>
                  {t.label}
                </Text>
              </TouchableOpacity>
            );
          })}
        </View>

        {/* Tab Content: Deals / Schedules List */}
        {activeTab === 'deals' && (
          <View style={styles.schedulesSection}>
            {loading ? (
              <ActivityIndicator size="large" color={COLORS.brandRed} style={{ marginVertical: 30 }} />
            ) : (
              schedules.map((item, idx) => {
                const isSelected = selectedSchedule?.id === item.id;
                const busName = item.bus?.name || 'Resi Bisma';
                const depTime = item.departure_time ? item.departure_time.substring(0, 5) : '07:00';
                const arrTime = item.arrival_time ? item.arrival_time.substring(0, 5) : '15:00';
                const price = Number(item.price || 180000).toLocaleString('id-ID');

                return (
                  <TouchableOpacity
                    key={item.id || idx}
                    activeOpacity={0.85}
                    onPress={() => setSelectedSchedule(item)}
                    style={[
                      styles.scheduleCard,
                      isSelected && styles.scheduleCardSelected,
                    ]}
                  >
                    <Image
                      source={idx % 2 === 0 ? require('../../assets/images/bentas02.webp') : require('../../assets/images/bentas03.webp')}
                      style={styles.scheduleImage}
                    />

                    <View style={styles.scheduleMiddle}>
                      <Text style={styles.scheduleTitle}>{busName}</Text>
                      <Text style={styles.scheduleTime}>
                        <Clock size={12} color={COLORS.brandRed} /> {depTime} - {arrTime} WIB
                      </Text>
                      <Text style={styles.schedulePriceText}>
                        Rp {price} <Text style={styles.schedulePriceSub}>/ Seat</Text>
                      </Text>
                    </View>

                    <View style={styles.scheduleRight}>
                      <View style={styles.ratingBadge}>
                        <Text style={styles.ratingBadgeText}>★ 4.8</Text>
                      </View>
                      <View style={[styles.selectCircle, isSelected && styles.selectCircleActive]}>
                        <ChevronRight size={16} color={isSelected ? '#FFFFFF' : COLORS.textMuted} />
                      </View>
                    </View>
                  </TouchableOpacity>
                );
              })
            )}
          </View>
        )}

        {/* Tab Content: Details */}
        {activeTab === 'details' && (
          <View style={styles.detailsBox}>
            <Text style={styles.detailsHeading}>Spesifikasi Armada &amp; Fasilitas</Text>
            <Text style={styles.detailsDesc}>
              Armada Executive Double Decker Tunggal Jaya Transport dengan sasis premium, suspensi udara, kabin kedap suara, dan kursi ergonomis recliner.
            </Text>
            <View style={styles.facilitiesList}>
              {['Leg Rest Ekstra Lebar', 'USB Charger Tiap Kursi', 'Toilet Standar Higienis', 'Free Snack & Air Mineral', 'Kru Pengemudi Berpengalaman'].map((f, i) => (
                <View key={i} style={styles.facilityItem}>
                  <CheckCircle size={16} color={COLORS.accentGold} />
                  <Text style={styles.facilityText}>{f}</Text>
                </View>
              ))}
            </View>
          </View>
        )}

        {/* Tab Content: Reviews */}
        {activeTab === 'reviews' && (
          <View style={styles.reviewsBox}>
            <View style={styles.reviewCard}>
              <View style={styles.reviewHeader}>
                <Text style={styles.reviewAuthor}>Rangga Pradana</Text>
                <Text style={styles.reviewRating}>★★★★★</Text>
              </View>
              <Text style={styles.reviewText}>
                Bus sangat nyaman, tepat waktu, dan driver menyetir dengan sangat halus. Rekomendasi utama rute Kuningan - Jakarta!
              </Text>
            </View>
          </View>
        )}

        {/* Bottom spacing for Floating Action Bar */}
        <View style={{ height: 120 }} />
      </ScrollView>

      {/* Floating Bottom Red Action Bar (Phone 3 Mockup) */}
      <View style={styles.bottomBarWrapper} pointerEvents="box-none">
        <View style={styles.bottomBarContainer}>
          <View style={styles.bottomBarLeft}>
            <View style={styles.seatIconCircle}>
              <User size={16} color={COLORS.brandRed} />
            </View>
            <View>
              <Text style={styles.bottomSeatLabel}>1 Seat • Executive</Text>
              <Text style={styles.bottomPriceValue}>
                Rp {Number(selectedSchedule?.price || 180000).toLocaleString('id-ID')}
              </Text>
            </View>
          </View>

          <TouchableOpacity
            activeOpacity={0.85}
            onPress={() => {
              if (selectedSchedule?.id) {
                navigation.navigate('SeatSelection', { scheduleId: selectedSchedule.id });
              }
            }}
            style={styles.bookNowBtn}
          >
            <Text style={styles.bookNowBtnText}>Book Now</Text>
          </TouchableOpacity>
        </View>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  heroContainer: {
    height: 250,
    width: '100%',
    position: 'relative',
  },
  heroImage: {
    width: '100%',
    height: '100%',
  },
  heroGradient: {
    ...StyleSheet.absoluteFillObject,
    justifyContent: 'space-between',
    paddingHorizontal: 20,
    paddingBottom: 16,
  },
  navBar: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingTop: Platform.OS === 'android' ? 12 : 6,
  },
  navIconBtn: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: 'rgba(22, 26, 34, 0.75)',
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.1)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  navTitle: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 16,
    color: '#FFFFFF',
  },
  routeHeaderOverlay: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-end',
  },
  routeHeaderLeft: {},
  routeHeaderTitle: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 22,
    color: '#FFFFFF',
    letterSpacing: -0.3,
  },
  routeHeaderSub: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 13,
    color: COLORS.textSecondary,
    marginTop: 2,
  },
  routeHeaderRight: {
    alignItems: 'flex-end',
  },
  starsRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
    backgroundColor: 'rgba(0, 0, 0, 0.4)',
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 10,
    marginBottom: 4,
  },
  starsText: {
    fontFamily: 'PlusJakartaSans_600SemiBold',
    fontSize: 11,
    color: '#FFFFFF',
  },
  routeHeaderPrice: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 18,
    color: '#FFFFFF',
  },
  routeHeaderPriceSub: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 12,
    color: COLORS.textMuted,
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingTop: 16,
  },
  tabsRow: {
    flexDirection: 'row',
    gap: 10,
    marginBottom: 20,
  },
  tabPill: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
    paddingHorizontal: 16,
    paddingVertical: 10,
    borderRadius: 22,
  },
  tabPillActive: {
    backgroundColor: COLORS.brandRed,
  },
  tabPillInactive: {
    backgroundColor: '#161922',
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.08)',
  },
  tabPillText: {
    fontFamily: 'PlusJakartaSans_600SemiBold',
    fontSize: 13,
  },
  tabPillTextActive: {
    color: '#FFFFFF',
  },
  tabPillTextInactive: {
    color: COLORS.textSecondary,
  },
  schedulesSection: {
    gap: 12,
  },
  scheduleCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#14171F',
    borderRadius: 18,
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.08)',
    padding: 12,
    gap: 14,
  },
  scheduleCardSelected: {
    borderColor: COLORS.brandRed,
    backgroundColor: '#181C26',
  },
  scheduleImage: {
    width: 68,
    height: 68,
    borderRadius: 14,
  },
  scheduleMiddle: {
    flex: 1,
  },
  scheduleTitle: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 15,
    color: '#FFFFFF',
    marginBottom: 2,
  },
  scheduleTime: {
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 12,
    color: COLORS.textSecondary,
    marginBottom: 6,
  },
  schedulePriceText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 15,
    color: '#FFFFFF',
  },
  schedulePriceSub: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 11,
    color: COLORS.textMuted,
  },
  scheduleRight: {
    alignItems: 'flex-end',
    gap: 10,
  },
  ratingBadge: {
    backgroundColor: 'rgba(255, 184, 0, 0.15)',
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 8,
  },
  ratingBadgeText: {
    fontFamily: 'PlusJakartaSans_600SemiBold',
    fontSize: 11,
    color: '#FFB800',
  },
  selectCircle: {
    width: 32,
    height: 32,
    borderRadius: 16,
    backgroundColor: '#1E222C',
    justifyContent: 'center',
    alignItems: 'center',
  },
  selectCircleActive: {
    backgroundColor: COLORS.brandRed,
  },
  detailsBox: {
    backgroundColor: '#14171F',
    borderRadius: 18,
    padding: 18,
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.08)',
  },
  detailsHeading: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 16,
    color: '#FFFFFF',
    marginBottom: 8,
  },
  detailsDesc: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 13,
    color: COLORS.textSecondary,
    lineHeight: 20,
    marginBottom: 16,
  },
  facilitiesList: {
    gap: 10,
  },
  facilityItem: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 10,
  },
  facilityText: {
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 13,
    color: '#FFFFFF',
  },
  reviewsBox: {
    gap: 12,
  },
  reviewCard: {
    backgroundColor: '#14171F',
    borderRadius: 16,
    padding: 16,
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.08)',
  },
  reviewHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 6,
  },
  reviewAuthor: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 14,
    color: '#FFFFFF',
  },
  reviewRating: {
    color: '#FFB800',
    fontSize: 12,
  },
  reviewText: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 13,
    color: COLORS.textSecondary,
    lineHeight: 19,
  },
  bottomBarWrapper: {
    position: 'absolute',
    bottom: Platform.OS === 'ios' ? 28 : 20,
    left: 20,
    right: 20,
  },
  bottomBarContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: COLORS.brandRed,
    paddingVertical: 10,
    paddingLeft: 16,
    paddingRight: 10,
    borderRadius: 36,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandRed,
        shadowOffset: { width: 0, height: 8 },
        shadowOpacity: 0.5,
        shadowRadius: 14,
      },
      android: {
        elevation: 10,
      },
    }),
  },
  bottomBarLeft: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 10,
  },
  seatIconCircle: {
    width: 36,
    height: 36,
    borderRadius: 18,
    backgroundColor: '#FFFFFF',
    justifyContent: 'center',
    alignItems: 'center',
  },
  bottomSeatLabel: {
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 11,
    color: 'rgba(255, 255, 255, 0.85)',
  },
  bottomPriceValue: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 16,
    color: '#FFFFFF',
  },
  bookNowBtn: {
    backgroundColor: '#FFFFFF',
    paddingVertical: 12,
    paddingHorizontal: 22,
    borderRadius: 28,
  },
  bookNowBtnText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 13,
    color: COLORS.brandRed,
  },
});
