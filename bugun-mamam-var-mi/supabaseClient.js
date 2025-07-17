import { createClient } from '@supabase/supabase-js';

const SUPABASE_URL = 'https://wzjjzdczullnjognnsxs.supabase.co'; // Replace with your Supabase project URL if different
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Ind6amp6ZGN6dWxsbmpvZ2duc3hzIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTI3NjYxNTQsImV4cCI6MjA2ODM0MjE1NH0.ivnqeaMqsWu1ug8v2c6O7yL2qHMQgu2EIuqc1WMtEdQ';

export const supabase = createClient(SUPABASE_URL, SUPABASE_ANON_KEY);