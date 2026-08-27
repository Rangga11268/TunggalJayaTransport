import React from 'react';
import { View, Text, StyleSheet, Image, TouchableOpacity, Dimensions } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { Colors, Radius } from '../theme/colors';
import { ChevronRight } from 'lucide-react-native';

const { height: SCREEN_HEIGHT } = Dimensions.get('window');

export default function GetStartedScreen({ navigation }: any) {
  const insets = useSafeAreaInsets();

  return (
    <View style={styles.container}>
      {/* Top Hero Image with Gradient */}
      <View style={styles.heroContainer}>
        <Image
          source={require('../../assets/images/heroImg.jpg')}
          style={styles.heroImage}
          resizeMode="cover"
        />
        <View style={styles.gradientOverlay} />

        {/* Top Header Pill: Brand Logo & Skip Link */}
        <View style={[styles.topBar, { top: insets.top + 16 }]}>
          <View style={styles.logoBadge}>
            <Image
              source={require('../../assets/logo/logoNoBg.png')}
              style={styles.logoIcon}
              resizeMode="contain"
            />
            <Text style={styles.brandTitle}>Tunggal Jaya</Text>
          </View>

          <TouchableOpacity
            style={styles.skipButton}
            onPress={() => navigation.replace('MainTabs')}
            activeOpacity={0.8}
          >
            <Text style={styles.skipText}>Skip</Text>
            <ChevronRight size={12} color="#FFFFFF" />
          </TouchableOpacity>
        </View>
      </View>

      {/* Bottom Dark Container Sheet */}
      <View style={[styles.bottomSheet, { paddingBottom: Math.max(insets.bottom + 16, 28) }]}>
        <View style={styles.content}>
          <Text style={styles.headline}>Cashback & Exclusive Deals</Text>
          <Text style={styles.subtitle}>
            Pesan tiket bus AKAP & sewa armada pariwisata Tunggal Jaya langsung dari smartphone Anda.
          </Text>

          {/* Dots Indicator */}
          <View style={styles.dotsRow}>
            <View style={styles.activeDot} />
            <View style={styles.dot} />
            <View style={styles.dot} />
          </View>
        </View>

        {/* Twin Action Buttons (Log in & Join) */}
        <View style={styles.buttonsRow}>
          <TouchableOpacity
            style={styles.loginBtn}
            onPress={() => navigation.navigate('Login')}
            activeOpacity={0.8}
          >
            <Text style={styles.loginBtnText}>Log in</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.joinBtn}
            onPress={() => navigation.navigate('Register')}
            activeOpacity={0.8}
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
    backgroundColor: Colors.background,
  },
  heroContainer: {
    height: SCREEN_HEIGHT * 0.62,
    width: '100%',
    position: 'relative',
  },
  heroImage: {
    width: '100%',
    height: '100%',
  },
  gradientOverlay: {
    ...StyleSheet.absoluteFillObject,
    backgroundColor: 'rgba(11, 11, 15, 0.4)',
  },
  topBar: {
    position: 'absolute',
    left: 24,
    right: 24,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    zIndex: 10,
  },
  logoBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'rgba(18, 18, 24, 0.85)',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: Radius.pill,
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.1)',
  },
  logoIcon: {
    width: 22,
    height: 22,
    marginRight: 8,
  },
  brandTitle: {
    color: '#FFFFFF',
    fontWeight: '900',
    fontSize: 14,
    letterSpacing: 0.5,
  },
  skipButton: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    paddingHorizontal: 14,
    paddingVertical: 7,
    borderRadius: Radius.pill,
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.15)',
  },
  skipText: {
    color: '#FFFFFF',
    fontSize: 12,
    fontWeight: '600',
    marginRight: 4,
  },
  bottomSheet: {
    flex: 1,
    backgroundColor: Colors.surfaceCard,
    borderTopLeftRadius: 36,
    borderTopRightRadius: 36,
    borderTopWidth: 1.5,
    borderTopColor: Colors.border,
    paddingHorizontal: 28,
    paddingTop: 28,
    justifyContent: 'space-between',
  },
  content: {
    alignItems: 'center',
  },
  headline: {
    fontSize: 24,
    fontWeight: '900',
    color: '#FFFFFF',
    textAlign: 'center',
    letterSpacing: -0.5,
  },
  subtitle: {
    fontSize: 13,
    color: Colors.textSecondary,
    textAlign: 'center',
    marginTop: 10,
    lineHeight: 20,
  },
  dotsRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 20,
  },
  activeDot: {
    width: 22,
    height: 6,
    backgroundColor: Colors.primary,
    borderRadius: Radius.full,
    marginRight: 6,
  },
  dot: {
    width: 6,
    height: 6,
    backgroundColor: Colors.surfaceHighest,
    borderRadius: Radius.full,
    marginRight: 6,
  },
  buttonsRow: {
    flexDirection: 'row',
    gap: 14,
  },
  loginBtn: {
    flex: 1,
    height: 52,
    backgroundColor: '#222232',
    borderRadius: Radius.pill,
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: Colors.borderLight,
  },
  loginBtnText: {
    color: '#FFFFFF',
    fontSize: 15,
    fontWeight: '700',
  },
  joinBtn: {
    flex: 1,
    height: 52,
    backgroundColor: Colors.primary,
    borderRadius: Radius.pill,
    justifyContent: 'center',
    alignItems: 'center',
    shadowColor: Colors.primary,
    shadowOffset: { width: 0, height: 6 },
    shadowOpacity: 0.4,
    shadowRadius: 16,
    elevation: 8,
  },
  joinBtnText: {
    color: '#FFFFFF',
    fontSize: 15,
    fontWeight: '800',
  },
});
