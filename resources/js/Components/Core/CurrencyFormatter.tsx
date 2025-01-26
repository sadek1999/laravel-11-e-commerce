

const CurrencyFormatter = ({
  amount,
  currency='USD',
  local,
}: {
  amount: number;
  currency?: string;
  local?: string;
}) => {
  return new Intl.NumberFormat(local, {
    style: "currency",
    currency,
  }).format(amount);
};

export default CurrencyFormatter;
