import { describe, it, expect } from 'vitest';
import { useEmailRule } from '../../composables/rules.js';

describe('useEmailRule', () => {
  it('should return true for valid email', () => {
    const validEmail = 'test.email.test@gmail.com';
    expect(useEmailRule(validEmail)).toBe(true);
  });

  it('should return false: no end domain', () => {
    const invalidEmail = 'test.email@gmail';
    expect(useEmailRule(invalidEmail)).toBe(false);
  });

  it('should return false: no domain name', () => {
    const invalidEmail = 'test.email@.com';
    expect(useEmailRule(invalidEmail)).toBe(false);
  });

  it('should return false: invalid domain name', () => {
    const invalidEmail = 'test.email@g!mail.com';
    expect(useEmailRule(invalidEmail)).toBe(false);
  });
});
