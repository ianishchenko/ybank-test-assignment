export default interface Transaction {
  id: number;
  from: number;
  to: number;
  details: string;
  amount: number|string
}
