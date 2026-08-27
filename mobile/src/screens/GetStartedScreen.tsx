import React, { useState, useRef } from 'react';
import {
  View,
  Text,
  StyleSheet,
  Image,
  TouchableOpacity,
  Dimensions,
  FlatList,
  Platform,
  NativeSyntheticEvent,
  NativeScrollEvent,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { useNavigation } from '@react-navigation/native';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { RootStackParamList } from '../navigation/RootNavigator';
import { COLORS } from '../theme/colors';
import { Tag, Crown, Zap, ChevronRight } from 'lucide-react-native';

const { width, height } = Dimensions.get('window');

type NavigationProp = NativeStackNavigationProp<RootStackParamList, 'GetStarted'>;

interface SlideItem {
  id: string;
  badge: string;
  badgeIcon: any;
  title: string;
  subtitle: string;
  image: any;
}

const SLIDES: SlideItem[] = [
  {
    id: '1',
    badge: 'PROMO SPESIAL',
    badgeIcon: Tag,
    title: 'Cashback & Offer',
    subtitle: 'Dapatkan diskon eksklusif, cashback voucher tiket, dan kumpulkan TJ Poin di setiap perjalanan Anda.',
    image: require('../../assets/images/bentas01.webp'),
  },
  {
    id: '2',
    badge: 'ARMADA MEWAH',
    badgeIcon: Crown,
    title: 'Executive Comfort',
    subtitle: 'Rasakan kenyamanan perjalanan antarkota dengan kursi leg rest ergonomis, full AC, dan toilet higienis.',
    image: require('../../assets/images/resiBisma.webp'),
  },
  {
    id: '3',
    badge: 'INSTANT BOOKING',
    badgeIcon: Zap,
    title: 'Pesan Tiket Mudah',
    subtitle: 'Pilih nomor kursi favorit langsung di denah bus, bayar praktis dengan QRIS realtime tanpa antre.',
    image: require('../../assets/images/kylorenParwis.webp'),
  },
];

export default function GetStartedScreen() {
  const navigation = useNavigation<NavigationProp>();
  const [activeIndex, setActiveIndex] = useState(0);
  const flatListRef = useRef<FlatList>(null);

  const onScroll = (event: NativeSyntheticEvent<NativeScrollEvent>) => {
    const slideSize = event.nativeEvent.layoutMeasurement.width;
    const offset = event.nativeEvent.contentOffset.x;
    const index = Math.round(offset / slideSize);
    if (index >= 0 && index < SLIDES.length && index !== activeIndex) {
      setActiveIndex(index);
    }
  };

  const currentSlide = SLIDES[activeIndex] || SLIDES[0];

  return (
    <View style={styles.container}>
      {/* Top 58% Hero Image Stage */}
      <View style={styles.heroStage}>
        <Image
          source={currentSlide.image}
          style={styles.heroImage}
          resizeMode="cover"
        />

        {/* Ambient Dark-to-Clear Gradient for Top Header visibility */}
        <LinearGradient
          colors={['rgba(17, 24, 39, 0.65)', 'transparent']}
          style={styles.topGradient}
        >
          <SafeAreaView edges={['top']} style={styles.topHeader}>
            <View style={styles.brandCapsule}>
              <View style={styles.brandRedDot} />
              <Text style={styles.brandTitle}>
                <Text style={{ color: COLORS.brandRed }}>Tunggal</Text> Jaya
              </Text>
            </View>

            <TouchableOpacity
              activeOpacity={0.8}
              onPress={() => navigation.replace('MainTabs')}
              style={styles.skipBtn}
            >
              <Text style={styles.skipText}>Lewati</Text>
              <ChevronRight size={14} color="#111827" />
            </TouchableOpacity>
          </SafeAreaView>
        </LinearGradient>
      </View>

      {/* Bottom Solid Card Sheet (Zero noise, High contrast) */}
      <View style={styles.cardSheet}>
        {/* Interactive 3-Slide Carousel */}
        <FlatList
          ref={flatListRef}
          data={SLIDES}
          keyExtractor={(item) => item.id}
          horizontal
          pagingEnabled
          showsHorizontalScrollIndicator={false}
          onScroll={onScroll}
          scrollEventThrottle={16}
          bounces={false}
          style={styles.carousel}
          renderItem={({ item }) => {
            const IconComp = item.badgeIcon;
            return (
              <View style={styles.slideItem}>
                <View style={styles.badgePill}>
                  <IconComp size={12} color={COLORS.brandRed} style={{ marginRight: 5 }} />
                  <Text style={styles.badgeText}>{item.badge}</Text>
                </View>

                <Text style={styles.slideTitle}>{item.title}</Text>
                <Text style={styles.slideSubtitle}>{item.subtitle}</Text>
              </View>
            );
          }}
        />

        {/* Carousel Pagination Dots */}
        <View style={styles.dotsRow}>
          {SLIDES.map((_, idx) => {
            const isActive = activeIndex === idx;
            return (
              <TouchableOpacity
                key={idx}
                activeOpacity={0.8}
                onPress={() => {
                  flatListRef.current?.scrollToIndex({ index: idx, animated: true });
                  setActiveIndex(idx);
                }}
                style={[styles.dot, isActive ? styles.dotActive : styles.dotInactive]}
              />
            );
          })}
        </View>

        {/* Twin Action Buttons (Ergonomic 52px height) */}
        <View style={styles.buttonRow}>
          <TouchableOpacity
            activeOpacity={0.85}
            style={styles.loginBtn}
            onPress={() => navigation.navigate('Login')}
          >
            <Text style={styles.loginBtnText}>Log in</Text>
          </TouchableOpacity>

          <TouchableOpacity
            activeOpacity={0.85}
            style={styles.joinBtn}
            onPress={() => navigation.navigate('Register')}
          >
            <Text style={styles.joinBtnText}>Join</Text>
          </TouchableOpacity>
        </View>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#F4F6F9',
  },
  heroStage: {
    flex: 1,
    width: '100%',
    position: 'relative',
    backgroundColor: '#1E293B',
  },
  heroImage: {
    width: '100%',
    height: '100%',
  },
  topGradient: {
    ...StyleSheet.absoluteFillObject,
    paddingHorizontal: 20,
  },
  topHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingTop: Platform.OS === 'android' ? 14 : 6,
  },
  brandCapsule: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    backgroundColor: 'rgba(255, 255, 255, 0.92)',
    paddingHorizontal: 14,
    paddingVertical: 8,
    borderRadius: 24,
    borderWidth: 1,
    borderColor: '#E2E8F0',
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.1,
        shadowRadius: 8,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  brandRedDot: {
    width: 10,
    height: 10,
    borderRadius: 5,
    backgroundColor: COLORS.brandRed,
  },
  brandTitle: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 16,
    color: '#111827',
    letterSpacing: -0.3,
  },
  skipBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
    backgroundColor: 'rgba(255, 255, 255, 0.92)',
    paddingHorizontal: 14,
    paddingVertical: 8,
    borderRadius: 20,
    borderWidth: 1,
    borderColor: '#E2E8F0',
  },
  skipText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 13,
    color: '#111827',
  },
  cardSheet: {
    backgroundColor: '#FFFFFF',
    borderTopLeftRadius: 32,
    borderTopRightRadius: 32,
    paddingTop: 24,
    paddingBottom: Platform.OS === 'ios' ? 40 : 28,
    paddingHorizontal: 24,
    marginTop: -28,
    borderTopWidth: 1,
    borderColor: '#E2E8F0',
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: -8 },
        shadowOpacity: 0.08,
        shadowRadius: 18,
      },
      android: {
        elevation: 10,
      },
    }),
  },
  carousel: {
    marginBottom: 16,
  },
  slideItem: {
    width: width - 48,
    alignItems: 'center',
  },
  badgePill: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'rgba(230, 0, 35, 0.08)',
    paddingHorizontal: 12,
    paddingVertical: 5,
    borderRadius: 14,
    marginBottom: 10,
  },
  badgeText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 11,
    color: COLORS.brandRed,
    letterSpacing: 0.5,
  },
  slideTitle: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 26,
    color: '#111827',
    textAlign: 'center',
    marginBottom: 8,
    letterSpacing: -0.5,
  },
  slideSubtitle: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 14,
    color: '#4B5563',
    textAlign: 'center',
    lineHeight: 22,
    paddingHorizontal: 12,
  },
  dotsRow: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    gap: 8,
    marginBottom: 24,
  },
  dot: {
    height: 6,
    borderRadius: 3,
  },
  dotActive: {
    width: 24,
    backgroundColor: COLORS.brandRed,
  },
  dotInactive: {
    width: 6,
    backgroundColor: '#CBD5E1',
  },
  buttonRow: {
    flexDirection: 'row',
    width: '100%',
    gap: 14,
  },
  loginBtn: {
    flex: 1,
    height: 52,
    borderRadius: 26,
    backgroundColor: '#F1F4F8',
    borderWidth: 1,
    borderColor: '#E2E8F0',
    justifyContent: 'center',
    alignItems: 'center',
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.04,
        shadowRadius: 6,
      },
      android: {
        elevation: 1,
      },
    }),
  },
  loginBtnText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 15,
    color: '#111827',
  },
  joinBtn: {
    flex: 1,
    height: 52,
    borderRadius: 26,
    backgroundColor: COLORS.brandRed,
    justifyContent: 'center',
    alignItems: 'center',
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
  joinBtnText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 15,
    color: '#FFFFFF',
  },
});
