import { PageProps, PaginationProps, TProduct } from '@/types';
import React from 'react';

const Home = ({
  products
}: PageProps<{products:PaginationProps<TProduct>}>) => {
  console.log(products)
  return (
    <div>

    </div>
  );
};

export default Home;
