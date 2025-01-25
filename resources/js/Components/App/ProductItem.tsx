import { TProduct } from "@/types";

const ProductItem = ({ product }: { product: TProduct }) => {
  return (
    <div>
      <div className="card bg-base-100 w-96 shadow-xl">
        <figure>
          <img src={product.image} alt="Shoes" />
        </figure>
        <div className="card-body">
          <h2 className="card-title">{product.title}</h2>
          <p>If a dog chews shoes whose shoes does he choose?</p>
          <p>{product.price}</p>
          <div className="card-actions justify-end">
            <button className="btn btn-primary">Buy Now</button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductItem;
