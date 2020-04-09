export default interface Transaction {
  id: number;
  from: number;
  to: number;
  details: string;
  amount: number;
  current_currency: number;
  created_at: string;
  updated_at: string;
}
