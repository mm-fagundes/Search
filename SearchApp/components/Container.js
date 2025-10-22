import React from 'react';
import { ScrollView, View, StyleSheet, SafeAreaView } from 'react-native';
import { colors, spacing } from '../theme';
import { useTheme } from '../contexts/ThemeContext';

const Container = ({
  children,
  scrollable = true,
  padding = 'lg',
  backgroundColor,
  safeArea = true,
  style,
  ...props
}) => {
  const { colors: themeColors } = useTheme();
  const bgColor = backgroundColor || themeColors.background;

  const getPaddingValue = () => {
    const paddingMap = {
      sm: spacing.md,
      md: spacing.lg,
      lg: spacing.xl,
      none: 0,
    };
    return paddingMap[padding] || spacing.xl;
  };

  const styles = StyleSheet.create({
    safeAreaView: {
      flex: 1,
      backgroundColor: bgColor,
    },
    scrollView: {
      flexGrow: 1,
      backgroundColor: bgColor,
    },
    content: {
      padding: getPaddingValue(),
    },
    viewContent: {
      flex: 1,
      padding: getPaddingValue(),
      backgroundColor: bgColor,
    },
  });

  const content = (
    <View style={[styles.content, style]} {...props}>
      {children}
    </View>
  );

  if (safeArea) {
    return (
      <SafeAreaView style={styles.safeAreaView}>
        {scrollable ? (
          <ScrollView
            style={styles.scrollView}
            contentContainerStyle={{ flexGrow: 1 }}
            showsVerticalScrollIndicator={false}
          >
            {content}
          </ScrollView>
        ) : (
          <View style={styles.viewContent}>{children}</View>
        )}
      </SafeAreaView>
    );
  }

  return scrollable ? (
    <ScrollView
      style={styles.scrollView}
      contentContainerStyle={{ flexGrow: 1 }}
      showsVerticalScrollIndicator={false}
    >
      {content}
    </ScrollView>
  ) : (
    <View style={[styles.viewContent, style]} {...props}>
      {children}
    </View>
  );
};

export default Container;

