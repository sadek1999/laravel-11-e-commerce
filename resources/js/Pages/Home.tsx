import Navbar from '@/Components/App/Navbar';
import ProductItem from '@/Components/App/ProductItem';
import { PageProps, PaginationProps, TProduct } from '@/types';


const Home = ({
  products
}: PageProps<{products:PaginationProps<TProduct>}>) => {
  console.log(products)
  return (
    <div>
          <Navbar></Navbar>
          {
            products.data.map(product=><ProductItem key={product.id} product={product}></ProductItem>)
          }
    </div>
  );
};

export default Home;
