import React from 'react';
import { View, TouchableOpacity, StyleSheet, Platform } from 'react-native';
import { BottomTabBarProps } from '@react-navigation/bottom-tabs';
import { Home, Calendar, Receipt, HelpCircle, User } from 'lucide-react-native';
import { COLORS } from '../theme/colors';

export default function FloatingTabBar({ state, descriptors, navigation }: BottomTabBarProps) {
  return (
    <View style={styles.floatingWrapper} pointerEvents="box-none">
      <View style={styles.container}>
        {state.routes.map((route, index) => {
          const isFocused = state.index === index;

          const onPress = () => {
            const event = navigation.emit({
              type: 'tabPress',
              target: route.key,
              canPreventDefault: true,
            });

            if (!isFocused && !event.defaultPrevented) {
              navigation.navigate(route.name);
            }
          };

          const renderIcon = () => {
            const color = isFocused ? '#FFFFFF' : '#64748B';
            const size = 19;

            switch (route.name) {
              case 'Home':
                return <Home size={size} color={color} strokeWidth={isFocused ? 2.4 : 1.8} />;
              case 'Schedules':
                return <Calendar size={size} color={color} strokeWidth={isFocused ? 2.4 : 1.8} />;
              case 'BookingHistory':
                return <Receipt size={size} color={color} strokeWidth={isFocused ? 2.4 : 1.8} />;
              case 'Help':
                return <HelpCircle size={size} color={color} strokeWidth={isFocused ? 2.4 : 1.8} />;
              case 'Profile':
                return <User size={size} color={color} strokeWidth={isFocused ? 2.4 : 1.8} />;
              default:
                return <Home size={size} color={color} strokeWidth={2} />;
            }
          };

          return (
            <TouchableOpacity
              key={route.key}
              activeOpacity={0.8}
              accessibilityRole="button"
              accessibilityState={isFocused ? { selected: true } : {}}
              onPress={onPress}
              style={[
                styles.tabButton,
                isFocused ? styles.tabButtonActive : styles.tabButtonInactive,
              ]}
            >
              {renderIcon()}
            </TouchableOpacity>
          );
        })}
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  floatingWrapper: {
    position: 'absolute',
    bottom: Platform.OS === 'ios' ? 26 : 18,
    left: 0,
    right: 0,
    alignItems: 'center',
    justifyContent: 'center',
  },
  container: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-around',
    backgroundColor: '#FFFFFF',
    paddingVertical: 6,
    paddingHorizontal: 8,
    borderRadius: 40,
    borderWidth: 1,
    borderColor: '#E2E8F0',
    width: '88%',
    maxWidth: 360,
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 10 },
        shadowOpacity: 0.12,
        shadowRadius: 18,
      },
      android: {
        elevation: 8,
      },
      web: {
        boxShadow: '0px 10px 25px rgba(15, 23, 42, 0.12)',
      },
    }),
  },
  tabButton: {
    width: 42,
    height: 42,
    borderRadius: 21,
    justifyContent: 'center',
    alignItems: 'center',
  },
  tabButtonActive: {
    backgroundColor: COLORS.brandRed,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandRed,
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.35,
        shadowRadius: 8,
      },
      android: {
        elevation: 6,
      },
      web: {
        boxShadow: '0px 4px 14px rgba(230, 0, 35, 0.4)',
      },
    }),
  },
  tabButtonInactive: {
    backgroundColor: 'transparent',
  },
});
