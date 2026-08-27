import React from 'react';
import { View, TouchableOpacity, StyleSheet, Platform } from 'react-native';
import { BottomTabBarProps } from '@react-navigation/bottom-tabs';
import { Home, Calendar, HelpCircle, User } from 'lucide-react-native';
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
            const color = isFocused ? '#FFFFFF' : COLORS.textMuted;
            const size = 20;

            switch (route.name) {
              case 'Home':
                return <Home size={size} color={color} strokeWidth={isFocused ? 2.4 : 1.8} />;
              case 'Schedules':
                return <Calendar size={size} color={color} strokeWidth={isFocused ? 2.4 : 1.8} />;
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
    bottom: Platform.OS === 'ios' ? 28 : 20,
    left: 0,
    right: 0,
    alignItems: 'center',
    justifyContent: 'center',
  },
  container: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    backgroundColor: '#13161C',
    paddingVertical: 6,
    paddingHorizontal: 12,
    borderRadius: 40,
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.1)',
    width: '78%',
    maxWidth: 320,
    ...Platform.select({
      ios: {
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 12 },
        shadowOpacity: 0.5,
        shadowRadius: 18,
      },
      android: {
        elevation: 14,
      },
      web: {
        boxShadow: '0px 14px 28px rgba(0, 0, 0, 0.65)',
      },
    }),
  },
  tabButton: {
    width: 46,
    height: 46,
    borderRadius: 23,
    justifyContent: 'center',
    alignItems: 'center',
  },
  tabButtonActive: {
    backgroundColor: COLORS.brandRed,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandRed,
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.5,
        shadowRadius: 8,
      },
      android: {
        elevation: 6,
      },
      web: {
        boxShadow: '0px 4px 14px rgba(255, 26, 53, 0.55)',
      },
    }),
  },
  tabButtonInactive: {
    backgroundColor: 'transparent',
  },
});
